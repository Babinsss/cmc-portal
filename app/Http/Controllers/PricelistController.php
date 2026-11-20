<?php

namespace App\Http\Controllers;

use App\Models\PricelistCategory;
use App\Models\PricelistItem;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PricelistController extends Controller
{
    public function index(Request $request): View
    {
        $categories = PricelistCategory::query()
            ->withCount('items')
            ->orderBy('sort_order')
            ->get();

        $activeCategorySlug = (string) $request->query('category', 'all');
        if ($activeCategorySlug !== 'all' && ! $categories->contains('slug', $activeCategorySlug)) {
            $activeCategorySlug = 'all';
        }

        $initialQuery = trim((string) $request->query('q', ''));

        $items = PricelistItem::query()
            ->with('category:id,name,slug')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get()
            ->map(fn (PricelistItem $item): array => [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'category_name' => $item->category?->name ?? '',
                'category_slug' => $item->category?->slug ?? '',
                'item_code' => $item->item_code,
                'name' => $item->name,
                'unit' => $item->unit ?? '—',
                'price' => (float) $item->price,
                'price_formatted' => number_format((float) $item->price, 2),
                'notes' => $item->notes ?? '',
                'is_available' => (bool) $item->is_available,
                'updated_at' => $item->updated_at?->format('M d, Y h:i A') ?? '—',
            ])
            ->values();

        return view('portal.pricelist', compact(
            'categories',
            'items',
            'activeCategorySlug',
            'initialQuery',
        ));
    }

    public function downloadPdf(?string $slug = null): Response
    {
        $query = PricelistCategory::query()
            ->with(['items' => fn ($q) => $q->orderBy('name')])
            ->orderBy('sort_order');

        if ($slug !== null) {
            $query->where('slug', $slug);
        }

        $categories = $query->get();

        abort_if($slug !== null && $categories->isEmpty(), 404);

        $settings = SiteSetting::allMap();
        $generatedAt = now()->format('F d, Y h:i A');

        $pdf = Pdf::loadView('pdf.pricelist', [
            'categories' => $categories,
            'settings' => $settings,
            'generatedAt' => $generatedAt,
            'singleCategory' => $slug !== null ? $categories->first() : null,
        ])->setPaper('a4', 'portrait');

        $filename = $slug !== null
            ? "cmc-citizens-charter-{$slug}.pdf"
            : 'cmc-citizens-charter-complete-pricelist.pdf';

        return $pdf->stream($filename);
    }
}
