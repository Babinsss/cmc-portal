<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use App\Models\PricelistCategory;
use App\Models\SiteSetting;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

#[Signature('portal:export-static {--output=dist : Output directory relative to project root (or absolute path)} {--base-path= : Optional subpath prefix for GitHub Pages project sites (e.g. /cmc-portal)}')]
#[Description('Export the public portal pages, Vite assets, images, and Citizen\'s Charter PDFs to a static bundle for Vercel or GitHub Pages.')]
class ExportStaticPortal extends Command
{
    private const EXPORT_ORIGIN = 'https://portal.export.local';

    /**
     * Execute the console command.
     */
    public function handle(Kernel $kernel): int
    {
        $outputDir = $this->resolveOutputDirectory((string) $this->option('output'));
        $basePath = $this->normalizeBasePath((string) $this->option('base-path'));

        if (! File::exists(public_path('build/manifest.json'))) {
            $this->warn('Vite manifest missing at public/build/manifest.json. Run "npm run build" first so CSS/JS assets are included.');
        }

        $previousStaticExport = config('cmc.static_export');
        $previousAppUrl = config('app.url');
        $previousAssetUrl = config('app.asset_url');
        $fakeRoot = self::EXPORT_ORIGIN.$basePath;

        File::deleteDirectory($outputDir);
        File::ensureDirectoryExists($outputDir);

        config([
            'cmc.static_export' => true,
            'app.url' => $fakeRoot,
            'app.asset_url' => null,
        ]);
        URL::forceRootUrl($fakeRoot);
        SiteSetting::clearCache();

        try {
            $categories = PricelistCategory::query()
                ->orderBy('sort_order')
                ->get(['slug']);

            $pdfMap = $this->buildPdfMap($categories->pluck('slug')->all(), $basePath);
            $routes = $this->collectPublicRoutes();

            $this->info('Exporting '.count($routes).' public HTML page(s)...');

            foreach ($routes as $uri => $relativeTarget) {
                $response = $this->dispatchGet($kernel, $uri);

                if ($response->getStatusCode() >= 400) {
                    $this->error("Failed rendering {$uri} (HTTP {$response->getStatusCode()}).");

                    return self::FAILURE;
                }

                $html = $this->rewriteHtmlForStatic((string) $response->getContent(), $basePath, $pdfMap);
                $destination = $outputDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativeTarget);
                File::ensureDirectoryExists(dirname($destination));
                File::put($destination, $html);
            }

            $this->info('Rendering '.count($pdfMap).' Citizen\'s Charter PDF document(s)...');

            foreach ($pdfMap as $pdfRoute => $pdfOutputPath) {
                $internalRoute = $basePath !== '' && str_starts_with($pdfRoute, $basePath)
                    ? substr($pdfRoute, strlen($basePath))
                    : $pdfRoute;

                $response = $this->dispatchGet($kernel, $internalRoute);

                if ($response->getStatusCode() >= 400) {
                    $this->error("Failed generating PDF for {$internalRoute} (HTTP {$response->getStatusCode()}).");

                    return self::FAILURE;
                }

                $relativePdfFile = ltrim(
                    $basePath !== '' && str_starts_with($pdfOutputPath, $basePath)
                        ? substr($pdfOutputPath, strlen($basePath))
                        : $pdfOutputPath,
                    '/',
                );

                $destination = $outputDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePdfFile);
                File::ensureDirectoryExists(dirname($destination));
                File::put($destination, (string) $response->getContent());
            }

            $this->copyPublicAssets($outputDir);
            $this->writeFallbackFiles($outputDir, $basePath);

            $this->newLine();
            $this->info("Static portal bundle written to: {$outputDir}");
        } finally {
            URL::forceRootUrl(null);
            config([
                'cmc.static_export' => $previousStaticExport,
                'app.url' => $previousAppUrl,
                'app.asset_url' => $previousAssetUrl,
            ]);
            SiteSetting::clearCache();
        }

        return self::SUCCESS;
    }

    /**
     * Collect static output targets for every public portal page.
     *
     * @return array<string, string>
     */
    private function collectPublicRoutes(): array
    {
        $routes = [
            '/' => 'index.html',
            '/services' => 'services/index.html',
            '/pricelist' => 'pricelist/index.html',
            '/about' => 'about/index.html',
            '/advisories' => 'advisories/index.html',
            '/careers' => 'careers/index.html',
            '/contact' => 'contact/index.html',
            '/privacy-notice' => 'privacy-notice/index.html',
        ];

        $slugs = Announcement::query()
            ->active()
            ->pluck('slug');

        foreach ($slugs as $slug) {
            $routes["/advisories/{$slug}"] = "advisories/{$slug}/index.html";
        }

        return $routes;
    }

    /**
     * Map dynamic PDF endpoints to pre-rendered static `.pdf` file paths.
     *
     * @param  list<string>  $categorySlugs
     * @return array<string, string>
     */
    private function buildPdfMap(array $categorySlugs, string $basePath): array
    {
        $map = [];

        foreach ($categorySlugs as $slug) {
            $map["{$basePath}/pricelist/{$slug}/pdf"] = "{$basePath}/pricelist/cmc-citizens-charter-{$slug}.pdf";
        }

        $map["{$basePath}/pricelist/pdf"] = "{$basePath}/pricelist/cmc-citizens-charter-complete-pricelist.pdf";

        return $map;
    }

    /**
     * Dispatch an internal GET request through Laravel's HTTP kernel.
     */
    private function dispatchGet(Kernel $kernel, string $uri): Response
    {
        $request = Request::create($uri, 'GET', server: [
            'HTTP_HOST' => 'portal.export.local',
            'HTTPS' => 'on',
        ]);

        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);

        return $response;
    }

    /**
     * Strip export origin hosts and point dynamic PDF routes to pre-rendered `.pdf` files.
     *
     * @param  array<string, string>  $pdfMap
     */
    private function rewriteHtmlForStatic(string $html, string $basePath, array $pdfMap): string
    {
        $slashVariants = ['\\\\\\/', '\\\\/', '\\/', '/'];

        foreach ($slashVariants as $slash) {
            $originVariant = str_replace('/', $slash, self::EXPORT_ORIGIN);
            $html = str_replace($originVariant, '', $html);

            foreach ($pdfMap as $routePath => $staticPdfPath) {
                $routeVariant = str_replace('/', $slash, $routePath);
                $pdfVariant = str_replace('/', $slash, $staticPdfPath);
                $html = str_replace($routeVariant, $pdfVariant, $html);
            }
        }

        if ($basePath !== '') {
            $escapedBasePath = str_replace('/', '\\/', $basePath);
            $homeHref = 'href="'.$basePath.'"';

            if (! str_contains($html, $homeHref.'/') && str_contains($html, $homeHref)) {
                $html = str_replace($homeHref, 'href="'.$basePath.'/"', $html);
            }

            $html = str_replace('href="\\/"', 'href="'.$escapedBasePath.'\\/"', $html);
        }

        return $html;
    }

    /**
     * Copy built Vite assets, images, and any uploaded public storage files into the export bundle.
     */
    private function copyPublicAssets(string $outputDir): void
    {
        $assetDirs = ['build', 'images', 'storage', 'documents'];

        foreach ($assetDirs as $dir) {
            $source = public_path($dir);
            if (! is_dir($source)) {
                continue;
            }

            File::copyDirectory($source, $outputDir.DIRECTORY_SEPARATOR.$dir);
        }
    }

    /**
     * Create `.nojekyll` and `404.html` fallback inside the exported directory.
     */
    private function writeFallbackFiles(string $outputDir, string $basePath): void
    {
        File::put($outputDir.DIRECTORY_SEPARATOR.'.nojekyll', '');

        $homeUrl = ($basePath !== '' ? $basePath : '').'/';
        $pricelistUrl = ($basePath !== '' ? $basePath : '').'/pricelist/';

        $notFoundHtml = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found — Capiz Medical Center</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; margin: 0; background: #f8fafc; color: #0a2240; display: grid; min-height: 100vh; place-items: center; padding: 1.5rem; }
        main { max-width: 32rem; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 1rem; padding: 2rem; text-align: center; box-shadow: 0 1px 3px rgba(10,34,64,.08); }
        h1 { font-size: 1.5rem; margin: 0 0 .5rem; }
        p { color: #475569; line-height: 1.6; margin: 0 0 1.5rem; font-size: .95rem; }
        .actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
        a { display: inline-flex; align-items: center; min-height: 44px; padding: .6rem 1.1rem; border-radius: .5rem; font-weight: 600; font-size: .875rem; text-decoration: none; }
        .primary { background: #0a2240; color: #ffffff; }
        .secondary { border: 1px solid #cbd5e1; color: #0a2240; background: #ffffff; }
    </style>
</head>
<body>
    <main>
        <h1>Page Not Found</h1>
        <p>The requested Capiz Medical Center page could not be found on this portal mirror.</p>
        <div class="actions">
            <a class="primary" href="{$homeUrl}">Return Home</a>
            <a class="secondary" href="{$pricelistUrl}">Open Citizen's Charter Pricelist</a>
        </div>
    </main>
</body>
</html>
HTML;

        File::put($outputDir.DIRECTORY_SEPARATOR.'404.html', $notFoundHtml);
    }

    private function resolveOutputDirectory(string $option): string
    {
        $trimmed = trim($option) !== '' ? trim($option) : 'dist';

        if (preg_match('/^(?:[A-Za-z]:[\\\\\\/]|\\/)/', $trimmed) === 1) {
            return rtrim($trimmed, '\\/');
        }

        return base_path(trim($trimmed, '\\/'));
    }

    private function normalizeBasePath(string $option): string
    {
        $trimmed = trim($option, " \t\n\r\0\x0B/");

        if ($trimmed === '') {
            return '';
        }

        return '/'.$trimmed;
    }
}
