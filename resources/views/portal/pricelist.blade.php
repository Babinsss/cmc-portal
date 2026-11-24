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
                <h1 class="text-3xl sm:text-4xl font-extrabold text-balance">Citizen's Charter Hospital Pricelist</h1>
                <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-xl leading-relaxed text-pretty">
                    Official DOH Citizen's Charter fee schedule. Search service codes, diagnostic procedures, medicines, supplies, PhilHealth packages, and room rates across all 8 sections.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 min-h-11 px-4 py-2.5 rounded-lg bg-white/12 border border-white/25 text-white text-xs font-semibold hover:bg-white/20 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/></svg>
                    Print View
                </button>
                <a href="{{ route('portal.pricelist.pdf.all') }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 min-h-11 px-4 py-2.5 rounded-lg bg-cmc-blue text-white text-xs font-semibold hover:bg-sky-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Download Complete Charter PDF
                </a>
            </div>
        </div>
    </section>

    <section x-data="{
        activeCategory: @js($activeCategorySlug),
        activeSubgroup: 'all',
        search: @js($initialQuery),
        currentPage: 1,
        perPage: 50,
        categories: @js($categoriesPayload),
        items: @js($items),
        allPdfUrl: @js(route('portal.pricelist.pdf.all')),
        init() {
            const params = new URLSearchParams(window.location.search);
            const qParam = params.get('q');
            const catParam = params.get('category');

            if (qParam !== null && qParam.trim() !== '') {
                this.search = qParam.trim();
            }

            if (catParam !== null && this.categories.some(c => c.slug === catParam)) {
                this.activeCategory = catParam;
            }

            this.$watch('search', () => { this.currentPage = 1; });
            this.$watch('activeCategory', () => { this.currentPage = 1; });
            this.$watch('activeSubgroup', () => { this.currentPage = 1; });
        },
        subgroupsByCategory: {
            'laboratory': [
                { key: 'all', label: 'All Laboratory', prefixes: [] },
                { key: 'chem', label: 'Blood Chemistry', prefixes: ['LAB-CHEM-'] },
                { key: 'hema', label: 'Hematology', prefixes: ['LAB-HEMA-'] },
                { key: 'sero', label: 'Serology', prefixes: ['LAB-SERO-'] },
                { key: 'bb', label: 'Blood Bank', prefixes: ['LAB-BB-'] },
                { key: 'micr', label: 'Microbiology', prefixes: ['LAB-MICR-'] },
                { key: 'mics', label: 'Microscopy', prefixes: ['LAB-MICS-'] },
                { key: 'hist', label: 'Histopath', prefixes: ['LAB-HIST-'] },
                { key: 'oth', label: 'Other Test', prefixes: ['LAB-OTH-'] },
            ],
            'pharmacy': [
                { key: 'all', label: 'All Pharmacy', prefixes: [] },
                { key: 'consigned', label: 'Consigned: Drugs & Medicine', prefixes: ['PHM-CS-'] },
                { key: 'non-consigned-iv', label: 'Non-Consigned: IV Fluids', prefixes: ['PHM-NC-IV-'] },
                { key: 'non-consigned-med', label: 'Non-Consigned: Medicines', prefixes: ['PHM-NC-MD-'] },
            ],
            'central-supply-room': [
                { key: 'all', label: 'All CSR Supplies', prefixes: [] },
                { key: 'med', label: 'Medical Supplies', prefixes: ['CSR-MED-'] },
                { key: 'sut', label: 'Sutures', prefixes: ['CSR-SUT-'] },
                { key: 'dia', label: 'Dialysis', prefixes: ['CSR-DIA-'] },
                { key: 'cpu', label: 'Cardio Pulmonary', prefixes: ['CSR-CPU-'] },
            ],
            'radiology': [
                { key: 'all', label: 'All Radiology', prefixes: [] },
                { key: 'xray-all', label: 'X-Ray: All', prefixes: ['RAD-XR-'] },
                { key: 'xray-skl', label: 'Skull Series', prefixes: ['RAD-XR-SKL-'] },
                { key: 'xray-thx', label: 'Thorax', prefixes: ['RAD-XR-THX-'] },
                { key: 'xray-abd', label: 'Abdomen', prefixes: ['RAD-XR-ABD-'] },
                { key: 'xray-ext', label: 'Extremities', prefixes: ['RAD-XR-EXT-'] },
                { key: 'xray-vrt', label: 'Vertebral Column', prefixes: ['RAD-XR-VRT-'] },
                { key: 'xray-spc', label: 'Special Procedures', prefixes: ['RAD-XR-SPC-'] },
                { key: 'ct', label: 'CT Scan (Jan 2026)', prefixes: ['RAD-CT-'] },
            ],
            'philhealth': [
                { key: 'all', label: 'All PhilHealth', prefixes: [] },
                { key: 'annex-a', label: 'Medical Case Rates (Annex A)', unitPrefix: 'Annex A:' },
                { key: 'annex-b', label: 'Procedure Case Rates (Annex B)', unitPrefix: 'Annex B:' },
                { key: 'annex-c', label: 'Excluded & Z-Benefits (Annex C)', unitPrefix: 'Annex C:', exactCodes: ['PHIC-NBB'] },
            ],
        },
        get activeCategoryObj() {
            return this.categories.find(c => c.slug === this.activeCategory) || null;
        },
        get activeSubgroups() {
            return this.subgroupsByCategory[this.activeCategory] || [];
        },
        get currentPdfUrl() {
            return this.activeCategoryObj ? this.activeCategoryObj.pdf_url : this.allPdfUrl;
        },
        get currentPdfLabel() {
            return this.activeCategoryObj ? ('Download ' + this.activeCategoryObj.name + ' PDF') : 'Download All Categories PDF';
        },
        matchesSearch(item, q) {
            if (q === '') return true;
            return (
                item.item_code.toLowerCase().includes(q) ||
                item.name.toLowerCase().includes(q) ||
                item.unit.toLowerCase().includes(q) ||
                item.notes.toLowerCase().includes(q) ||
                item.category_name.toLowerCase().includes(q)
            );
        },
        matchesSubgroupRule(item, rule) {
            if (!rule || rule.key === 'all') return true;
            if (rule.exactCodes && rule.exactCodes.includes(item.item_code)) return true;
            if (rule.unitPrefix && item.unit.startsWith(rule.unitPrefix)) return true;
            if (rule.prefixes && rule.prefixes.length > 0) {
                return rule.prefixes.some(prefix => item.item_code.startsWith(prefix));
            }
            return false;
        },
        matchesSubgroup(item, categorySlug, subgroupKey) {
            if (subgroupKey === 'all') return true;
            const list = this.subgroupsByCategory[categorySlug] || [];
            const found = list.find(g => g.key === subgroupKey);
            return this.matchesSubgroupRule(item, found);
        },
        get searchFilteredItems() {
            const q = this.search.trim().toLowerCase();
            if (q === '') {
                return this.items;
            }
            return this.items.filter(item => this.matchesSearch(item, q));
        },
        get categoryCounts() {
            const counts = { all: this.searchFilteredItems.length };
            for (const item of this.searchFilteredItems) {
                counts[item.category_slug] = (counts[item.category_slug] || 0) + 1;
            }
            return counts;
        },
        get filteredItems() {
            const base = this.searchFilteredItems;
            if (this.activeCategory === 'all') {
                return base;
            }
            const hasSubgroups = this.activeSubgroups.length > 0;
            return base.filter(item => {
                if (item.category_slug !== this.activeCategory) return false;
                if (hasSubgroups && !this.matchesSubgroup(item, this.activeCategory, this.activeSubgroup)) return false;
                return true;
            });
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredItems.length / this.perPage));
        },
        get paginatedItems() {
            const safePage = Math.min(this.currentPage, this.totalPages);
            const start = (safePage - 1) * this.perPage;
            return this.filteredItems.slice(start, start + this.perPage);
        },
        get pageStart() {
            if (this.filteredItems.length === 0) return 0;
            const safePage = Math.min(this.currentPage, this.totalPages);
            return (safePage - 1) * this.perPage + 1;
        },
        get pageEnd() {
            if (this.filteredItems.length === 0) return 0;
            const safePage = Math.min(this.currentPage, this.totalPages);
            return Math.min(safePage * this.perPage, this.filteredItems.length);
        },
        countForCategory(slug) {
            return this.categoryCounts[slug] || 0;
        },
        get subgroupCounts() {
            const groups = this.activeSubgroups;
            const counts = {};
            if (groups.length === 0) return counts;
            for (const g of groups) {
                counts[g.key] = 0;
            }
            for (const item of this.searchFilteredItems) {
                if (item.category_slug !== this.activeCategory) continue;
                counts.all = (counts.all || 0) + 1;
                for (const g of groups) {
                    if (g.key !== 'all' && this.matchesSubgroupRule(item, g)) {
                        counts[g.key]++;
                    }
                }
            }
            return counts;
        },
        countForSubgroup(subgroupKey) {
            return this.subgroupCounts[subgroupKey] || 0;
        }
    }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Search Controls --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm mb-6 no-print space-y-4">
            <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">
                <div class="relative flex-1">
                    <label for="pricelist-search" class="sr-only">Search hospital pricelist</label>
                    <svg class="w-5 h-5 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    <input id="pricelist-search" type="text" x-model.debounce.150ms="search"
                           placeholder="Search code, diagnosis, procedure, medicine, or room (e.g. A00.0, CBC, Hemodialysis)..."
                           class="w-full min-h-11 pl-10 pr-11 py-3 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder:text-slate-500 focus:border-cmc-blue">
                    <button type="button" x-show="search !== ''" x-cloak @click="search = ''" aria-label="Clear search"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-800 min-h-10 min-w-10 inline-flex items-center justify-center rounded-md cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a :href="currentPdfUrl" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 min-h-11 px-4 py-2 rounded-lg bg-cmc-navy text-white text-xs font-semibold hover:bg-cmc-blue transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        <span x-text="currentPdfLabel"></span>
                    </a>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600">
                    <span>Quick examples:</span>
                    @foreach (['CBC', 'X-Ray', 'CT Scan', 'Private Room', 'Paracetamol', 'Hemodialysis', 'Dengue', 'Ultrasound'] as $chip)
                        <button type="button" @click="search = '{{ $chip }}'; activeCategory = 'all'; activeSubgroup = 'all'"
                                class="min-h-11 px-3 py-1.5 rounded-full bg-slate-100 hover:bg-sky-50 hover:text-cmc-blue font-medium transition cursor-pointer">
                            {{ $chip }}
                        </button>
                    @endforeach
                </div>

                <div class="text-xs text-slate-600 tabular-nums">
                    Showing <span class="font-bold text-cmc-navy" x-text="pageStart.toLocaleString() + '–' + pageEnd.toLocaleString()"></span> of <span class="font-bold text-cmc-navy" x-text="filteredItems.length.toLocaleString()"></span> matching rates (<span x-text="items.length.toLocaleString()"></span> total)
                    <button type="button" x-show="search !== '' || activeCategory !== 'all' || activeSubgroup !== 'all'" x-cloak
                            @click="search = ''; activeCategory = 'all'; activeSubgroup = 'all'"
                            class="ml-2 inline-flex items-center min-h-11 px-1.5 text-cmc-blue font-semibold underline underline-offset-2 cursor-pointer">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- 8 Category Tabs --}}
        <div role="tablist" aria-label="Hospital fee categories" class="flex flex-wrap gap-2 mb-6 no-print">
            <button type="button" role="tab" :aria-selected="(activeCategory === 'all').toString()" @click="activeCategory = 'all'; activeSubgroup = 'all'"
                    :class="activeCategory === 'all' ? 'bg-cmc-navy text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200/80 hover:border-cmc-blue/50'"
                    class="min-h-11 px-3 py-2 rounded-lg text-xs font-semibold transition cursor-pointer inline-flex items-center gap-1.5">
                <span>All Sections</span>
                <span class="px-1.5 py-0.5 rounded-full text-xs tabular-nums"
                      :class="activeCategory === 'all' ? 'bg-white text-cmc-navy' : 'bg-slate-100 text-slate-700'"
                      x-text="countForCategory('all').toLocaleString()"></span>
            </button>

            <template x-for="cat in categories" :key="cat.id">
                <button type="button" role="tab" :aria-selected="(activeCategory === cat.slug).toString()" @click="activeCategory = cat.slug; activeSubgroup = 'all'"
                        :class="activeCategory === cat.slug ? 'bg-cmc-blue text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200/80 hover:border-cmc-blue/50'"
                        class="min-h-11 px-3 py-2 rounded-lg text-xs font-semibold transition cursor-pointer inline-flex items-center gap-1.5">
                    <span x-text="cat.name"></span>
                    <span class="px-1.5 py-0.5 rounded-full text-xs tabular-nums"
                          :class="activeCategory === cat.slug ? 'bg-white text-cmc-navy' : 'bg-slate-100 text-slate-700'"
                          x-text="countForCategory(cat.slug).toLocaleString()"></span>
                </button>
            </template>
        </div>

        {{-- Active Category Info Banner + Subgroup Filter --}}
        <template x-if="activeCategoryObj">
            <div class="bg-sky-50/70 border border-slate-200/80 rounded-xl p-4 mb-5 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-cmc-navy text-base text-balance" x-text="activeCategoryObj.name"></h2>
                        <p class="text-xs text-slate-700 mt-0.5 max-w-2xl text-pretty" x-text="activeCategoryObj.description"></p>
                    </div>
                    <div class="text-xs text-slate-600 shrink-0 tabular-nums">
                        Last updated: <span class="font-semibold text-cmc-navy" x-text="activeCategoryObj.last_updated"></span>
                    </div>
                </div>

                <div x-show="activeSubgroups.length > 0" class="pt-3 border-t border-slate-200/80 flex flex-wrap items-center gap-2 no-print" role="group" aria-label="Section unit filter">
                    <template x-for="grp in activeSubgroups" :key="grp.key">
                        <button type="button" @click="activeSubgroup = grp.key"
                                :class="activeSubgroup === grp.key ? 'bg-cmc-navy text-white' : 'bg-white text-slate-700 border border-slate-200/80 hover:border-cmc-blue/50'"
                                class="min-h-11 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer inline-flex items-center gap-1.5">
                            <span x-text="grp.label"></span>
                            <span class="tabular-nums text-xs opacity-85" x-text="'(' + countForSubgroup(grp.key).toLocaleString() + ')'"></span>
                        </button>
                    </template>
                </div>

                <div x-show="activeCategory === 'philhealth'" x-cloak class="pt-3 border-t border-slate-200/80 flex flex-wrap items-center gap-2 no-print">
                    <span class="text-xs font-semibold text-cmc-navy mr-1">Official PhilHealth Annex PDFs:</span>
                    <a href="{{ asset('documents/philhealth/annex-a-medical-case-rates.pdf') }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 min-h-11 px-3 py-1.5 rounded-lg bg-white border border-slate-200/80 text-xs font-semibold text-cmc-navy hover:border-cmc-blue/50 hover:text-cmc-blue transition">
                        Annex A: Medical Case Rates PDF
                    </a>
                    <a href="{{ asset('documents/philhealth/annex-b-procedure-case-rates.pdf') }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 min-h-11 px-3 py-1.5 rounded-lg bg-white border border-slate-200/80 text-xs font-semibold text-cmc-navy hover:border-cmc-blue/50 hover:text-cmc-blue transition">
                        Annex B: Procedure Case Rates PDF
                    </a>
                    <a href="{{ asset('documents/philhealth/annex-c-excluded-z-benefits.pdf') }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 min-h-11 px-3 py-1.5 rounded-lg bg-white border border-slate-200/80 text-xs font-semibold text-cmc-navy hover:border-cmc-blue/50 hover:text-cmc-blue transition">
                        Annex C: Excluded &amp; Z-Benefits PDF
                    </a>
                </div>
            </div>
        </template>

        <h2 class="sr-only" x-show="!activeCategoryObj">All Citizen's Charter Hospital Fee Items</h2>

        {{-- Print Header (Visible only when printing) --}}
        <div class="hidden print:block mb-4 border-b border-slate-300 pb-3">
            <h2 class="text-xl font-bold text-slate-900">{{ $settings['hospital_name'] ?? 'Capiz Medical Center' }} — Citizen's Charter Pricelist</h2>
            <p class="text-xs text-slate-600">{{ $settings['location'] ?? 'Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }} | Printed {{ now()->format('F d, Y h:i A') }}</p>
        </div>

        {{-- Mobile Card List (hidden on md+ and print) --}}
        <div class="md:hidden print:hidden space-y-3">
            <template x-if="filteredItems.length === 0">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-8 text-center text-sm text-slate-600">
                    No matching items found. Try another keyword or switch back to
                    <button type="button" @click="activeCategory = 'all'; activeSubgroup = 'all'; search = ''" class="text-cmc-blue font-semibold underline cursor-pointer">All Sections</button>.
                </div>
            </template>

            <template x-for="item in paginatedItems" :key="'m-' + item.id">
                <article class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm space-y-2">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-semibold text-cmc-blue" x-text="item.item_code"></span>
                                <span class="text-xs font-medium text-slate-600" x-show="activeCategory === 'all'" x-text="item.category_name"></span>
                            </div>
                            <h3 class="font-semibold text-sm text-slate-900 mt-1 text-balance break-words" x-text="item.name"></h3>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-bold text-base text-cmc-navy tabular-nums">
                                <span x-show="item.unit === 'Annex C: Succeeding Sched.'" x-cloak class="text-xs font-semibold text-cmc-blue">See Schedule</span>
                                <span x-show="item.unit !== 'Annex C: Succeeding Sched.'">&#8369;<span x-text="item.price_formatted"></span></span>
                            </div>
                            <div class="text-xs text-slate-600" x-text="item.unit"></div>
                        </div>
                    </div>

                    <p x-show="item.notes" class="text-xs text-slate-600 leading-relaxed text-pretty" x-text="item.notes"></p>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                        <span x-show="!item.is_available" x-cloak class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold bg-rose-100 text-rose-800">
                            Unavailable
                        </span>
                        <span class="text-slate-600 tabular-nums ml-auto" x-text="'Updated ' + item.updated_at"></span>
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
                            <th scope="col" class="px-4 py-3.5">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <template x-if="filteredItems.length === 0">
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-600">
                                    No matching items found. Try another keyword or switch back to <button type="button" @click="activeCategory = 'all'; activeSubgroup = 'all'; search = ''" class="text-cmc-blue font-semibold underline cursor-pointer">All Sections</button>.
                                </td>
                            </tr>
                        </template>

                        <template x-for="item in paginatedItems" :key="item.id">
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-4 py-3.5 whitespace-nowrap font-mono text-xs font-semibold text-cmc-blue" x-text="item.item_code"></td>
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-slate-900" x-text="item.name"></div>
                                    <div x-show="item.notes" class="text-xs text-slate-600 mt-0.5" x-text="item.notes"></div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs font-medium text-slate-600" x-show="activeCategory === 'all'" x-text="item.category_name"></td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-600" x-text="item.unit"></td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-right font-bold text-cmc-navy tabular-nums">
                                    <span x-show="item.unit === 'Annex C: Succeeding Sched.'" x-cloak class="text-xs font-semibold text-cmc-blue">See Schedule</span>
                                    <span x-show="item.unit !== 'Annex C: Succeeding Sched.'">&#8369;<span x-text="item.price_formatted"></span></span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-500 tabular-nums" x-text="item.updated_at"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Controls --}}
        <div x-show="totalPages > 1" x-cloak class="mt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white rounded-xl border border-slate-200/80 px-4 py-3 shadow-xs no-print">
            <p class="text-xs text-slate-600 tabular-nums">
                Showing <span class="font-semibold text-cmc-navy" x-text="pageStart.toLocaleString() + '–' + pageEnd.toLocaleString()"></span>
                of <span class="font-semibold text-cmc-navy" x-text="filteredItems.length.toLocaleString()"></span> rates
                (Page <span class="font-semibold text-cmc-navy" x-text="currentPage.toLocaleString()"></span> of <span x-text="totalPages.toLocaleString()"></span>)
            </p>
            <div class="flex items-center gap-2">
                <button type="button"
                        @click="currentPage = Math.max(1, currentPage - 1); window.scrollTo({ top: 0, behavior: 'smooth' })"
                        :disabled="currentPage <= 1"
                        class="min-h-11 px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-cmc-navy hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                    Previous
                </button>
                <button type="button"
                        @click="currentPage = Math.min(totalPages, currentPage + 1); window.scrollTo({ top: 0, behavior: 'smooth' })"
                        :disabled="currentPage >= totalPages"
                        class="min-h-11 px-4 py-2 rounded-lg bg-cmc-navy text-white text-xs font-semibold hover:bg-cmc-blue disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                    Next
                </button>
            </div>
        </div>

        <p class="mt-4 text-xs text-slate-600 max-w-md leading-relaxed text-pretty">
            Citizen's Charter Fee Rules: Rates published here are official hospital reference fees before PhilHealth deductions. Verify No Balance Billing and social service assistance at the Billing Desk with your PhilHealth MDR/ID, Senior Citizen, PWD, or Indigency records.
        </p>
    </section>
@endsection
