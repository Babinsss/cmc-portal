<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExportStaticPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_static_export_generates_pages_pdfs_and_rewrites_links(): void
    {
        $exportDir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'cmc_static_export_'.uniqid('', true);

        try {
            $this->artisan('portal:export-static', [
                '--output' => $exportDir,
                '--base-path' => '/cmc-portal',
            ])->assertExitCode(0);

            $expectedFiles = [
                'index.html',
                '404.html',
                '.nojekyll',
                'services/index.html',
                'pricelist/index.html',
                'about/index.html',
                'advisories/index.html',
                'advisories/psgs-accredited-general-surgery-residency-open/index.html',
                'advisories/pcp-accredited-internal-medicine-residency-open/index.html',
                'careers/index.html',
                'contact/index.html',
                'privacy-notice/index.html',
                'pricelist/cmc-citizens-charter-complete-pricelist.pdf',
                'pricelist/cmc-citizens-charter-laboratory.pdf',
                'pricelist/cmc-citizens-charter-pharmacy.pdf',
                'pricelist/cmc-citizens-charter-radiology.pdf',
                'pricelist/cmc-citizens-charter-ultrasound.pdf',
                'images/cmc-logo-official.jpg',
            ];

            foreach ($expectedFiles as $relativePath) {
                $fullPath = $exportDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
                $this->assertFileExists($fullPath, "Expected exported file {$relativePath} to exist.");
            }

            $homeHtml = File::get($exportDir.DIRECTORY_SEPARATOR.'index.html');
            $this->assertStringNotContainsString('portal.export.local', $homeHtml);
            $this->assertStringNotContainsString('Staff Admin', $homeHtml);
            $this->assertStringContainsString('/cmc-portal/pricelist/cmc-citizens-charter-complete-pricelist.pdf', $homeHtml);

            $pricelistHtml = File::get($exportDir.DIRECTORY_SEPARATOR.'pricelist'.DIRECTORY_SEPARATOR.'index.html');
            $this->assertStringNotContainsString('portal.export.local', $pricelistHtml);
            $this->assertStringContainsString('cmc-citizens-charter-laboratory.pdf', $pricelistHtml);
            $this->assertStringContainsString('URLSearchParams', $pricelistHtml);

            $contactHtml = File::get($exportDir.DIRECTORY_SEPARATOR.'contact'.DIRECTORY_SEPARATOR.'index.html');
            $this->assertStringContainsString('Direct Assistance &amp; Inquiries', $contactHtml);
            $this->assertStringNotContainsString('Submit Local Inquiry', $contactHtml);

            $pdfBytes = File::get($exportDir.DIRECTORY_SEPARATOR.'pricelist'.DIRECTORY_SEPARATOR.'cmc-citizens-charter-ultrasound.pdf');
            $this->assertStringStartsWith('%PDF-', $pdfBytes);
        } finally {
            File::deleteDirectory($exportDir);
        }
    }
}
