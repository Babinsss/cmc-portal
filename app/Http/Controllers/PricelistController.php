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
            ->orderByRaw("CASE WHEN item_code = 'PHIC-NBB' THEN 0 ELSE 1 END")
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
        $highlightPhilhealthCodes = [
            'PHIC-NBB',
            'KONSULTA',
            '90935',
            '59513',
            'A97.2',
            'A00.0',
            'A01.0',
            'J18.9',
            'J18.99, Y95',
            'I10.9',
            'IHDAMIA',
            'Z0011',
        ];

        $categories = PricelistCategory::query()
            ->withCount('items')
            ->when($slug !== null, fn ($query) => $query->where('slug', $slug))
            ->orderBy('sort_order')
            ->get();

        abort_if($slug !== null && $categories->isEmpty(), 404);

        $summaryNotices = [];

        foreach ($categories as $category) {
            if ($category->slug === 'philhealth' && $category->items_count > 100) {
                $featured = PricelistItem::query()
                    ->where('category_id', $category->id)
                    ->whereIn('item_code', $highlightPhilhealthCodes)
                    ->orderByRaw("CASE WHEN item_code = 'PHIC-NBB' THEN 0 ELSE 1 END")
                    ->orderBy('name')
                    ->get();

                $category->setRelation('items', $featured);
                $summaryNotices[$category->id] = sprintf(
                    'Showing %d key reference packages out of %s official PhilHealth case rates (Annex A Medical, Annex B Procedure, Annex C Excluded & Z-Benefits). Search the full schedule online or download the complete official Annex PDFs from the portal.',
                    $featured->count(),
                    number_format((int) $category->items_count),
                );
            } else {
                $category->load(['items' => fn ($q) => $q->orderBy('name')]);
            }
        }

        $settings = SiteSetting::allMap();
        $generatedAt = now()->format('F d, Y h:i A');

        $pdf = Pdf::loadView('pdf.pricelist', [
            'categories' => $categories,
            'settings' => $settings,
            'generatedAt' => $generatedAt,
            'singleCategory' => $slug !== null ? $categories->first() : null,
            'summaryNotices' => $summaryNotices,
        ])->setPaper('a4', 'portrait');

        $filename = $slug !== null
            ? "cmc-citizens-charter-{$slug}.pdf"
            : 'cmc-citizens-charter-complete-pricelist.pdf';

        return $pdf->stream($filename);
    }
}
