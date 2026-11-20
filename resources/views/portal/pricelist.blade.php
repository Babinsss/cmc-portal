@extends('layouts.app')

@section('title', "Citizen's Charter Pricelist — " . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@php
    $categoriesPayload = $categories->map(fn ($c) => [
        'id' => $c->id,
        'name' => $c->name,
        'slug' => $c->slug,
        'description' => $c->description ?? '',
        'items_count' => $c->items_count,
        'last_updated' => $c->last_updated_at?->format('M d, Y h:i A') ?? '—',
        'pdf_url' => route('portal.pricelist.pdf', $c->slug),
    ])->values();
@endphp

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold">Citizen's Charter Hospital Pricelist</h1>
                <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-3xl leading-relaxed">
                    Official DOH Citizen's Charter schedule of fees. Search service codes, diagnostic procedures, medicines, supplies, PhilHealth packages, and room rates across all 8 hospital sections.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-white/12 border border-white/25 text-white text-xs font-semibold hover:bg-white/20 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/></svg>
                    Print View
                </button>
                <a href="{{ route('portal.pricelist.pdf.all') }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-cmc-teal text-white text-xs font-semibold hover:bg-teal-800 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Download Complete Charter PDF
                </a>
            </div>
        </div>
    </section>

    <section x-data="{
        activeCategory: @js($activeCategorySlug),
        search: @js($initialQuery),
        onlyAvailable: false,
        categories: @js($categoriesPayload),
        items: @js($items),
        allPdfUrl: @js(route('portal.pricelist.pdf.all')),
        get activeCategoryObj() {
            return this.categories.find(c => c.slug === this.activeCategory) || null;
        },
        get currentPdfUrl() {
            return this.activeCategoryObj ? this.activeCategoryObj.pdf_url : this.allPdfUrl;
        },
        get currentPdfLabel() {
            return this.activeCategoryObj ? ('Download ' + this.activeCategoryObj.name + ' PDF') : 'Download All Categories PDF';
        },
        get filteredItems() {
            const q = this.search.trim().toLowerCase();
            return this.items.filter(item => {
                if (this.activeCategory !== 'all' && item.category_slug !== this.activeCategory) return false;
                if (this.onlyAvailable && !item.is_available) return false;
                if (q === '') return true;
                return (
                    item.item_code.toLowerCase().includes(q) ||
                    item.name.toLowerCase().includes(q) ||
                    item.unit.toLowerCase().includes(q) ||
                    item.notes.toLowerCase().includes(q) ||
                    item.category_name.toLowerCase().includes(q)
                );
            });
        },
        countForCategory(slug) {
            const q = this.search.trim().toLowerCase();
            return this.items.filter(item => {
                if (slug !== 'all' && item.category_slug !== slug) return false;
                if (this.onlyAvailable && !item.is_available) return false;
                if (q === '') return true;
                return (
                    item.item_code.toLowerCase().includes(q) ||
                    item.name.toLowerCase().includes(q) ||
                    item.unit.toLowerCase().includes(q) ||
                    item.notes.toLowerCase().includes(q) ||
                    item.category_name.toLowerCase().includes(q)
                );
            }).length;
        }
    }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Search Controls --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm mb-6 no-print space-y-4">
            <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">
                <div class="relative flex-1">
                    <label for="pricelist-search" class="sr-only">Search hospital pricelist</label>
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    <input id="pricelist-search" type="text" x-model="search"
                           placeholder="Search item code, procedure, medicine, or room (e.g. CBC, Chest X-Ray)..."
                           class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-300 text-sm focus:border-cmc-teal focus:outline-none">
                    <button type="button" x-show="search !== ''" x-cloak @click="search = ''" aria-label="Clear search"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-800 p-1 rounded-md cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-lg border border-slate-200 cursor-pointer select-none">
                        <input type="checkbox" x-model="onlyAvailable" class="rounded border-slate-300 text-cmc-teal focus:ring-cmc-teal">
                        Available items only
                    </label>

                    <a :href="currentPdfUrl" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-cmc-navy text-white text-xs font-semibold hover:bg-cmc-blue transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        <span x-text="currentPdfLabel"></span>
                    </a>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-1 border-t border-slate-100">
                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600">
                    <span>Quick examples:</span>
                    @foreach (['CBC', 'Chest X-Ray', 'Private Room', 'Paracetamol', 'Ultrasound', 'ECG'] as $chip)
                        <button type="button" @click="search = '{{ $chip }}'; activeCategory = 'all'"
                                class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-teal-50 hover:text-cmc-teal font-medium transition cursor-pointer">
                            {{ $chip }}
                        </button>
                    @endforeach
                </div>

                <div class="text-xs text-slate-600 tabular-nums">
                    Showing <span class="font-bold text-cmc-navy" x-text="filteredItems.length"></span> of <span x-text="items.length"></span> rates
                    <button type="button" x-show="search !== '' || activeCategory !== 'all' || onlyAvailable" x-cloak
                            @click="search = ''; activeCategory = 'all'; onlyAvailable = false"
                            class="ml-2 text-cmc-teal font-semibold underline underline-offset-2 cursor-pointer">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- 8 Category Tabs --}}
        <div role="tablist" aria-label="Hospital fee categories" class="flex flex-wrap gap-1.5 mb-6 no-print">
            <button type="button" role="tab" :aria-selected="(activeCategory === 'all').toString()" @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-cmc-navy text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:border-cmc-teal'"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer inline-flex items-center gap-1.5">
                <span>All Sections</span>
                <span class="px-1.5 py-0.5 rounded-full text-[11px] tabular-nums"
                      :class="activeCategory === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'"
                      x-text="countForCategory('all')"></span>
            </button>

            <template x-for="cat in categories" :key="cat.id">
                <button type="button" role="tab" :aria-selected="(activeCategory === cat.slug).toString()" @click="activeCategory = cat.slug"
                        :class="activeCategory === cat.slug ? 'bg-cmc-teal text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:border-cmc-teal'"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer inline-flex items-center gap-1.5">
                    <span x-text="cat.name"></span>
                    <span class="px-1.5 py-0.5 rounded-full text-[11px] tabular-nums"
                          :class="activeCategory === cat.slug ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'"
                          x-text="countForCategory(cat.slug)"></span>
                </button>
            </template>
        </div>

        {{-- Active Category Info Banner --}}
        <template x-if="activeCategoryObj">
            <div class="bg-teal-50/70 border border-cmc-teal/30 rounded-xl p-4 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="font-bold text-cmc-navy text-base" x-text="activeCategoryObj.name"></h2>
                    <p class="text-xs text-slate-600 mt-0.5" x-text="activeCategoryObj.description"></p>
                </div>
                <div class="text-xs text-slate-600 shrink-0 tabular-nums">
                    Last updated: <span class="font-semibold text-cmc-navy" x-text="activeCategoryObj.last_updated"></span>
                </div>
            </div>
        </template>

        {{-- Print Header (Visible only when printing) --}}
        <div class="hidden print:block mb-4 border-b border-slate-300 pb-3">
            <h2 class="text-xl font-bold text-slate-900">{{ $settings['hospital_name'] ?? 'Capiz Medical Center' }} — Citizen's Charter Pricelist</h2>
            <p class="text-xs text-slate-600">{{ $settings['location'] ?? 'Sitio Nasagud, Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }} | Printed {{ now()->format('F d, Y h:i A') }}</p>
        </div>

        {{-- Mobile Card List (hidden on md+ and print) --}}
        <div class="md:hidden print:hidden space-y-3">
            <template x-if="filteredItems.length === 0">
                <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-sm text-slate-600">
                    No matching items found. Try another keyword or switch back to
                    <button type="button" @click="activeCategory = 'all'; search = ''; onlyAvailable = false" class="text-cmc-teal font-semibold underline cursor-pointer">All Sections</button>.
                </div>
            </template>

            <template x-for="item in filteredItems" :key="'m-' + item.id">
                <article class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm space-y-2">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-semibold text-cmc-blue" x-text="item.item_code"></span>
                                <span class="text-[11px] font-medium text-slate-500" x-show="activeCategory === 'all'" x-text="item.category_name"></span>
                            </div>
                            <h3 class="font-semibold text-sm text-slate-900 mt-1" x-text="item.name"></h3>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-bold text-base text-cmc-navy tabular-nums">
                                &#8369;<span x-text="item.price_formatted"></span>
                            </div>
                            <div class="text-[11px] text-slate-500" x-text="item.unit"></div>
                        </div>
                    </div>

                    <p x-show="item.notes" class="text-xs text-slate-600 leading-relaxed" x-text="item.notes"></p>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 text-[11px]">
                        <span x-show="item.is_available" class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold bg-emerald-100 text-emerald-800">
                            Available
                        </span>
                        <span x-show="!item.is_available" x-cloak class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold bg-rose-100 text-rose-800">
                            Unavailable
                        </span>
                        <span class="text-slate-500 tabular-nums" x-text="'Updated ' + item.updated_at"></span>
                    </div>
                </article>
            </template>
        </div>

        {{-- Desktop & Print Table --}}
        <div class="hidden md:block print:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wider text-slate-600">
                        <tr>
                            <th scope="col" class="px-4 py-3.5">Code</th>
                            <th scope="col" class="px-4 py-3.5">Description</th>
                            <th scope="col" class="px-4 py-3.5" x-show="activeCategory === 'all'">Section</th>
                            <th scope="col" class="px-4 py-3.5">Unit / Spec</th>
                            <th scope="col" class="px-4 py-3.5 text-right">Rate (PHP)</th>
                            <th scope="col" class="px-4 py-3.5 text-center">Status</th>
                            <th scope="col" class="px-4 py-3.5">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <template x-if="filteredItems.length === 0">
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-600">
                                    No matching items found. Try another keyword or switch back to <button type="button" @click="activeCategory = 'all'; search = ''; onlyAvailable = false" class="text-cmc-teal font-semibold underline cursor-pointer">All Sections</button>.
                                </td>
                            </tr>
                        </template>

                        <template x-for="item in filteredItems" :key="item.id">
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 whitespace-nowrap font-mono text-xs font-semibold text-cmc-blue" x-text="item.item_code"></td>
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-slate-900" x-text="item.name"></div>
                                    <div x-show="item.notes" class="text-xs text-slate-600 mt-0.5" x-text="item.notes"></div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs font-medium text-slate-600" x-show="activeCategory === 'all'" x-text="item.category_name"></td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-600" x-text="item.unit"></td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-right font-bold text-cmc-navy tabular-nums">
                                    &#8369;<span x-text="item.price_formatted"></span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                    <span x-show="item.is_available" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Available
                                    </span>
                                    <span x-show="!item.is_available" x-cloak class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                        Unavailable
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-500 tabular-nums" x-text="item.updated_at"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="mt-4 text-xs text-slate-600 leading-relaxed">
            Citizen's Charter Fee Rules: Rates published here are official government hospital reference fees before PhilHealth deductions. Bring your PhilHealth MDR/ID, Senior Citizen, PWD, or Indigency documents to verify No Balance Billing (walang dagdag-bayad sa service ward) and social service assistance at the Billing Desk.
        </p>
    </section>
@endsection
