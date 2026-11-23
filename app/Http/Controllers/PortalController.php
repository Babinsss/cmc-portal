<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Inquiry;
use App\Models\JobPosting;
use App\Models\PricelistCategory;
use App\Models\PricelistItem;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function home(): View
    {
        $announcements = Announcement::query()
            ->active()
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->limit(4)
            ->get();

        $highlightedServices = Service::query()
            ->where('is_active', true)
            ->with('subspecialties')
            ->orderByDesc('is_highlighted')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $pricelistCategories = PricelistCategory::query()
            ->withCount('items')
            ->orderBy('sort_order')
            ->get();

        $sampleItemsByCategory = PricelistItem::query()
            ->whereIn('id', function ($sub): void {
                $sub->from('pricelist_items')
                    ->selectRaw('MIN(id)')
                    ->where('is_available', true)
                    ->groupBy('category_id');
            })
            ->get()
            ->keyBy('category_id');

        $pricelistCategories->each(function (PricelistCategory $category) use ($sampleItemsByCategory): void {
            $sample = $sampleItemsByCategory->get($category->id);
            $category->setRelation('items', $sample ? collect([$sample]) : collect());
        });

        return view('portal.home', compact(
            'announcements',
            'highlightedServices',
            'pricelistCategories',
        ));
    }

    public function services(): View
    {
        $services = Service::query()
            ->where('is_active', true)
            ->with('subspecialties')
            ->orderBy('sort_order')
            ->get();

        return view('portal.services', compact('services'));
    }

    public function about(): View
    {
        return view('portal.about');
    }

    public function advisories(): View
    {
        $announcements = Announcement::query()
            ->active()
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('portal.advisories', compact('announcements'));
    }

    public function advisoryShow(string $slug): View
    {
        $announcement = Announcement::query()
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('portal.advisory-show', compact('announcement'));
    }

    public function careers(): View
    {
        $jobs = JobPosting::query()
            ->where('is_open', true)
            ->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END')
            ->orderBy('deadline')
            ->latest()
            ->get();

        return view('portal.careers', compact('jobs'));
    }

    public function contact(): View
    {
        return view('portal.contact');
    }

    public function submitInquiry(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'contact_info' => ['required', 'string', 'max:150'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:3000'],
            'privacy_consented' => ['accepted'],
        ], [
            'privacy_consented.accepted' => 'You must acknowledge the Data Privacy Notice (RA 10173) before submitting your inquiry.',
        ]);

        Inquiry::query()->create([
            'name' => $validated['name'],
            'contact_info' => $validated['contact_info'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'privacy_consented' => true,
            'status' => Inquiry::STATUS_NEW,
        ]);

        return redirect()
            ->route('portal.contact')
            ->with('inquiry_success', 'Your inquiry has been recorded in our local assistance desk queue. Thank you for contacting Capiz Medical Center.');
    }

    public function privacyNotice(): View
    {
        return view('portal.privacy-notice');
    }
}
