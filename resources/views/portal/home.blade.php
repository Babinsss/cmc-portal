@extends('layouts.app')

@section('title', ($settings['hospital_name'] ?? 'Capiz Medical Center') . ' — Official Web Portal')

@php
    $heroImageUrl = \App\Models\SiteSetting::resolveAssetUrl('hero_image_path', 'images/cmc-building.jpg');
    $npcSealUrl = \App\Models\SiteSetting::resolveAssetUrl('npc_seal_path', 'images/npc-seal-official.jpg');
    $officialLogoUrl = file_exists(public_path('images/cmc-logo-official.jpg'))
        ? asset('images/cmc-logo-official.jpg')
        : asset('images/cmc-logo.svg');
    $googleMapsUrl = $settings['google_maps_url'] ?? 'https://www.google.com/maps/search/?api=1&query=Roxas+Memorial+Provincial+Hospital+Outpatient+Department,+Brgy.+Lanot,+Roxas+City,+Capiz';
    $goals = json_decode($settings['goals'] ?? '[]', true);
    if (! is_array($goals) || $goals === []) {
        $goals = [
            'Ensure accessible and equitable healthcare services',
            'Enhance comprehensive and specialized healthcare services',
            'Promote compassionate and patient-centered care',
            'Strengthen healthcare quality and operational excellence',
            'Develop competent, empowered, and service-oriented healthcare professionals',
            'Improve health outcomes and community well-being',
        ];
    }

    $categoryExamples = [
        'laboratory' => 'Blood chemistry, CBC & urinalysis',
        'radiology' => 'X-rays & CT scans with reading',
        'ultrasound' => 'Whole abdomen, KUB, KUBP & guided',
        'cardio-pulmonary-unit' => 'ABG, ECG, oxygen therapy & ventilators',
        'pharmacy' => 'Essential medicines (DOH DPRI)',
        'central-supply-room' => 'Medical supplies, sutures & dialysis',
        'room-rates' => 'Service wards, private rooms & ICU',
        'philhealth' => 'Case packages & No Balance Billing',
    ];

    $diagnosticSlugs = ['laboratory', 'radiology', 'ultrasound', 'cardio-pulmonary-unit'];
    $diagnosticCategories = $pricelistCategories->whereIn('slug', $diagnosticSlugs);
    $billingCategories = $pricelistCategories->whereNotIn('slug', $diagnosticSlugs);
@endphp

@section('content')
    {{-- Hero & Citizen's Charter Rate Finder (Logo Palette + CMC Building) --}}
    <section class="relative bg-sky-50 text-cmc-navy overflow-hidden border-b border-slate-200/80">
        <img src="{{ $heroImageUrl }}" alt="" aria-hidden="true" width="1024" height="768"
             class="absolute inset-0 w-full h-full object-cover object-right-bottom pointer-events-none select-none">
        <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-r from-white/90 via-sky-50/75 to-white/25 pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-10 items-center">
                <div class="lg:col-span-7 space-y-3.5 sm:space-y-5">
                    <div class="inline-flex flex-wrap items-center gap-x-2 gap-y-0.5 px-3.5 py-1 rounded-xl sm:rounded-full bg-white/95 border border-slate-200/80 text-xs font-semibold text-cmc-navy shadow-xs">
                        <span>DOH &amp; PhilHealth Regulated</span>
                        <span class="hidden sm:inline" aria-hidden="true">&bull;</span>
                        <span>{{ $settings['hospital_classification'] ?? 'Level II Government Hospital' }}</span>
                        <span aria-hidden="true">&bull;</span>
                        <span class="tabular-nums">{{ $settings['authorized_beds'] ?? '147' }} Beds</span>
                    </div>

                    <div class="space-y-1.5 sm:space-y-2">
                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-[1.12] text-balance text-cmc-navy">
                            {{ $settings['hospital_name'] ?? 'Capiz Medical Center' }}
                        </h1>
                        <p class="text-base sm:text-xl font-semibold text-cmc-blue italic">
                            "{{ $settings['tagline'] ?? 'Pag-atipan nga may gugma' }}"
                        </p>
                        <p class="text-xs sm:text-base text-slate-800 max-w-xl leading-relaxed font-medium text-pretty">
                            24/7 emergency care and transparent Citizen's Charter hospital fees across Roxas City and Capiz.
                        </p>
                    </div>

                    {{-- Quick Pricelist Search Bar --}}
                    <form action="{{ route('portal.pricelist') }}" method="GET"
                          x-data="{ query: '', emptySearchError: false }"
                          @submit="if (query.trim() === '') { $event.preventDefault(); emptySearchError = true; $refs.heroSearchInput.focus(); } else { query = query.trim(); emptySearchError = false; }"
                          class="max-w-xl space-y-2">
                        <label for="hero-pricelist-search" class="block text-xs font-bold text-cmc-navy">
                            Search official hospital rates (Pangitaa ang bayad):
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2 bg-white/95 p-1.5 rounded-xl border border-slate-200/90 shadow-md">
                            <div class="relative flex-1">
                                <svg class="w-5 h-5 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                                <input id="hero-pricelist-search"
                                       x-ref="heroSearchInput"
                                       x-model="query"
                                       @input="if (query.trim() !== '') emptySearchError = false"
                                       type="text" name="q"
                                       placeholder="Search rates (e.g. CBC, X-Ray, Room)..."
                                       class="w-full pl-10 pr-4 py-2.5 min-h-11 rounded-lg bg-white text-slate-900 placeholder-slate-500 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cmc-blue focus-visible:ring-2 focus-visible:ring-cmc-blue">
                            </div>
                            <button type="submit" class="px-5 py-2.5 min-h-11 rounded-lg bg-amber-400 hover:bg-amber-300 text-cmc-navy font-bold text-sm transition shrink-0 cursor-pointer shadow-xs">
                                Search Rates
                            </button>
                        </div>
                        <p x-show="emptySearchError" x-cloak role="alert" class="text-xs font-bold text-cmc-red bg-white/95 px-3 py-1.5 rounded-lg w-fit border border-slate-200">
                            Please enter a test, medicine, or room name.
                        </p>
                        <div class="flex flex-wrap items-center gap-1.5 text-xs text-cmc-navy">
                            <span class="font-semibold bg-white/90 px-2.5 py-1 rounded-full border border-slate-200/80 mr-0.5">Common:</span>
                            @foreach (['CBC', 'X-Ray', 'Ultrasound', 'Private Room'] as $quickTerm)
                                <a href="{{ route('portal.pricelist', ['q' => $quickTerm]) }}"
                                   class="inline-flex items-center min-h-11 px-3 py-2 rounded-full bg-white/95 hover:bg-cmc-navy hover:text-white border border-slate-200/80 text-cmc-navy font-semibold transition shadow-xs">
                                    {{ $quickTerm }}
                                </a>
                            @endforeach
                        </div>
                    </form>

                    <div>
                        <a href="{{ route('portal.services') }}" class="inline-flex items-center gap-2 min-h-11 px-5 py-2.5 rounded-lg bg-cmc-navy hover:bg-cmc-blue text-white font-semibold text-sm transition shadow-sm">
                            Explore Medical Services &rarr;
                        </a>
                    </div>
                </div>

                {{-- Single Compact Campus & Triage Card with Official Seal (96px+) --}}
                <div class="lg:col-span-5">
                    <div class="bg-white/95 text-slate-800 rounded-2xl shadow-lg border border-slate-200/80 overflow-hidden p-5 sm:p-6 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-full bg-white p-1.5 shadow-sm border border-slate-200/80 shrink-0 flex items-center justify-center">
                                <img src="{{ $officialLogoUrl }}" alt="{{ $settings['hospital_name'] ?? 'Capiz Medical Center' }} Official Seal"
                                     width="1024" height="1024"
                                     class="w-full h-full object-contain rounded-full">
                            </div>
                            <div class="min-w-0 space-y-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-cmc-red text-white text-[11px] font-bold tracking-wide uppercase">
                                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                    24/7 Emergency &amp; Triage
                                </span>
                                <h2 class="text-sm sm:text-base font-extrabold text-cmc-navy leading-snug text-balance">
                                    {{ $settings['hospital_name'] ?? 'Capiz Medical Center' }}
                                </h2>
                                <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-start gap-1 text-xs font-medium text-cmc-blue hover:text-cmc-navy underline underline-offset-2 leading-snug text-pretty">
                                    <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    <span>{{ $settings['location'] ?? 'Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }}</span>
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-slate-100 text-xs">
                            <span class="text-slate-600 font-medium">OPD: Mon–Fri 8 AM–4 PM</span>
                            <a href="{{ route('portal.contact') }}#campus-map" class="inline-flex items-center gap-1 min-h-11 font-bold text-cmc-red hover:text-red-800 transition shrink-0">
                                Emergency &amp; Map &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 1: Citizen's Charter Rates (Soft Sky-Blue Wash, Subtle Borders) --}}
    <section class="bg-sky-50/50 border-b border-slate-200/80 py-11">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-cmc-navy text-balance">Citizen's Charter Rate Categories</h2>
                    <p class="text-sm text-slate-600 mt-1 text-pretty max-w-xl">Official DOH / ARTA fee guide (Talaan sang Opisyal nga Bayad).</p>
                </div>
                <a href="{{ route('portal.pricelist.pdf.all') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 min-h-11 px-4 py-2.5 rounded-lg bg-cmc-navy text-white text-xs font-semibold hover:bg-cmc-blue transition w-fit shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Download Full Fee Guide (PDF)
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-cmc-blue text-balance">Diagnostics &amp; Clinical Tests</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($diagnosticCategories as $category)
                            @php
                                $sampleItem = $category->items->first();
                                $exampleText = $categoryExamples[$category->slug] ?? \Illuminate\Support\Str::limit($category->description ?? '', 44);
                            @endphp
                            <a href="{{ route('portal.pricelist', ['category' => $category->slug]) }}"
                               class="group p-4 min-h-11 rounded-xl bg-white border border-slate-200/80 hover:border-cmc-blue/50 transition flex flex-col justify-between gap-2.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <h4 class="font-semibold text-sm text-cmc-navy group-hover:text-cmc-blue transition text-balance">{{ $category->name }}</h4>
                                        <p class="text-xs text-slate-600 mt-0.5 leading-snug text-pretty">{{ $exampleText }}</p>
                                    </div>
                                    <span class="text-cmc-blue font-semibold text-sm shrink-0 group-hover:translate-x-0.5 transition" aria-hidden="true">&rarr;</span>
                                </div>
                                <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-600 tabular-nums">
                                    <span>{{ number_format((int) $category->items_count) }} rates</span>
                                    @if ($sampleItem)
                                        <span class="font-semibold text-cmc-navy">
                                            from &#8369;{{ number_format((float) $sampleItem->price, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-cmc-blue text-balance">Medicines, Supplies &amp; Billing</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($billingCategories as $category)
                            @php
                                $sampleItem = $category->items->first();
                                $exampleText = $categoryExamples[$category->slug] ?? \Illuminate\Support\Str::limit($category->description ?? '', 44);
                            @endphp
                            <a href="{{ route('portal.pricelist', ['category' => $category->slug]) }}"
                               class="group p-4 min-h-11 rounded-xl bg-white border border-slate-200/80 hover:border-cmc-blue/50 transition flex flex-col justify-between gap-2.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <h4 class="font-semibold text-sm text-cmc-navy group-hover:text-cmc-blue transition text-balance">{{ $category->name }}</h4>
                                        <p class="text-xs text-slate-600 mt-0.5 leading-snug text-pretty">{{ $exampleText }}</p>
                                    </div>
                                    <span class="text-cmc-blue font-semibold text-sm shrink-0 group-hover:translate-x-0.5 transition" aria-hidden="true">&rarr;</span>
                                </div>
                                <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-600 tabular-nums">
                                    <span>{{ number_format((int) $category->items_count) }} rates</span>
                                    @if ($sampleItem)
                                        <span class="font-semibold text-cmc-navy">
                                            @if ((float) $sampleItem->price <= 0)
                                                Zero co-pay (NBB)
                                            @else
                                                e.g. &#8369;{{ number_format((float) $sampleItem->price, 2) }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <p class="text-xs text-slate-700 pt-3 border-t border-slate-200/80 max-w-md leading-relaxed text-pretty">
                <span class="font-semibold text-cmc-navy">PhilHealth &amp; NBB Note:</span>
                Posted prices are reference fees before PhilHealth deductions. Present PhilHealth MDR/ID, Senior Citizen, PWD, or Indigency records at Billing for No Balance Billing verification.
            </p>
        </div>
    </section>

    {{-- Section 2: Hospital Advisories (Clean White Surface, Subtle Borders) --}}
    <section class="bg-white border-b border-slate-200/80 py-11">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-cmc-navy text-balance">Hospital Advisories (Mga Pahibalo)</h2>
                    <p class="text-sm text-slate-600 mt-1 text-pretty">Official bulletins, clinic schedules, and residency notices.</p>
                </div>
                <a href="{{ route('portal.advisories') }}" class="inline-flex items-center min-h-11 text-sm font-semibold text-cmc-blue hover:text-cmc-navy transition shrink-0">
                    View all advisories &rarr;
                </a>
            </div>

            @if ($announcements->isEmpty())
                <div class="bg-slate-50 rounded-xl border border-slate-200/80 p-8 text-center text-sm text-slate-600">
                    No active hospital advisories at the moment.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($announcements as $advisory)
                        <article class="bg-white rounded-xl border border-slate-200/80 hover:border-cmc-blue/50 p-6 flex flex-col justify-between transition">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if ($advisory->is_pinned)
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-200/80">Pinned</span>
                                        @endif
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">{{ $advisory->category }}</span>
                                    </div>
                                    <time class="text-xs text-slate-600 tabular-nums shrink-0">{{ ($advisory->published_at ?? $advisory->created_at)->format('M d, Y') }}</time>
                                </div>
                                <h3 class="font-bold text-base sm:text-lg text-cmc-navy leading-snug text-balance">
                                    <a href="{{ route('portal.advisory.show', $advisory->slug) }}" class="hover:text-cmc-blue transition">
                                        {{ $advisory->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-slate-600 mt-2 line-clamp-2 leading-relaxed text-pretty">
                                    {{ $advisory->summary ?: \Illuminate\Support\Str::limit(strip_tags($advisory->body), 130) }}
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100">
                                <a href="{{ route('portal.advisory.show', $advisory->slug) }}" class="inline-flex items-center min-h-11 text-xs font-semibold text-cmc-blue hover:text-cmc-navy transition">
                                    Read full notice &rarr;
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Section 3: Featured Clinical Services & Institutional Goals (Soft Neutral Tint) --}}
    <section class="bg-slate-50/80 border-b border-slate-200/80 py-11">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-9">
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-cmc-navy text-balance">Medical Services (Mga Serbisyo)</h2>
                        <p class="text-sm text-slate-600 mt-1 text-pretty">24/7 emergency &amp; inpatient divisions, weekday OPD clinics, and dialysis.</p>
                    </div>
                    <a href="{{ route('portal.services') }}" class="inline-flex items-center min-h-11 text-sm font-semibold text-cmc-blue hover:text-cmc-navy transition shrink-0">
                        View all services &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($highlightedServices as $service)
                        <a href="{{ route('portal.services') }}"
                           class="group rounded-xl p-6 border bg-white border-slate-200/80 hover:border-cmc-blue/50 flex flex-col justify-between transition">
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2.5">
                                    <div>
                                        <span class="text-xs font-bold text-cmc-blue">Service {{ $service->code }}</span>
                                        <h3 class="text-lg font-bold text-cmc-navy mt-0.5 group-hover:text-cmc-blue transition text-balance">{{ $service->name }}</h3>
                                    </div>
                                    <span class="self-start sm:shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $service->is_24_7 ? 'bg-sky-50 text-cmc-blue border border-slate-200/80' : 'bg-amber-50 text-amber-900 border border-amber-200/80' }}">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $service->is_24_7 ? 'bg-cmc-blue' : 'bg-amber-600' }}"></span>
                                        {{ $service->schedule_label }}
                                    </span>
                                </div>
                                @if ($service->description)
                                    <p class="text-sm text-slate-600 mt-2.5 leading-relaxed line-clamp-2 text-pretty">{{ $service->description }}</p>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 space-y-2.5">
                                @if ($service->subspecialties->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($service->subspecialties->take(3) as $sub)
                                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">{{ $sub->name }}</span>
                                        @endforeach
                                        @if ($service->subspecialties->count() > 3)
                                            <span class="px-2.5 py-1 rounded-md bg-sky-50 text-cmc-blue text-xs font-semibold tabular-nums">+{{ $service->subspecialties->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="text-xs font-semibold text-cmc-blue group-hover:text-cmc-navy transition">
                                    View schedule &amp; units &rarr;
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Concise OPD / ER Intake Steps --}}
                <div class="border-t border-slate-200/80 pt-5 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <h3 class="font-bold text-cmc-navy text-balance">Emergency Room Intake (24/7)</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed text-pretty">
                            Go directly to ER triage in Brgy. Lanot. Immediate medical stabilization comes before billing paperwork.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-bold text-cmc-navy text-balance">Out-Patient Steps (OPD · Mon–Fri, 8 AM–4 PM)</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed text-pretty">
                            1. Register at OPD Triage &rarr; 2. Present valid ID &amp; referral &rarr; 3. Verify rates or PhilHealth before payment.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Institutional Goals (Compact Grid) --}}
            <div class="border-t border-slate-200/80 pt-7">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <h2 class="text-xl font-bold text-cmc-navy text-balance">Public Healthcare Goals</h2>
                    <a href="{{ route('portal.about') }}" class="inline-flex items-center min-h-11 text-xs font-semibold text-cmc-blue hover:text-cmc-navy transition shrink-0">
                        Vision &amp; Mission &rarr;
                    </a>
                </div>
                <ol class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                    @foreach ($goals as $index => $goal)
                        <li class="py-1.5 flex items-baseline gap-3 text-sm text-slate-700 leading-relaxed text-pretty">
                            <span class="font-bold text-cmc-blue tabular-nums shrink-0">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <span>{{ $goal }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- Section 4: Compact Closing Section (Campus Visit & RA 10173 Privacy Notice) --}}
    <section class="bg-white py-11">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                {{-- Campus Visit & Assistance --}}
                <div class="lg:col-span-6 space-y-3.5">
                    <h2 class="text-2xl font-bold text-cmc-navy leading-snug text-balance">Campus Visit &amp; Assistance</h2>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-lg text-pretty">
                        Visit us at
                        <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer"
                           class="font-semibold text-cmc-blue hover:text-cmc-navy underline underline-offset-4">
                            {{ $settings['location'] ?? 'Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }}
                        </a>
                        for 24/7 emergency care or weekday outpatient clinics (8 AM–4 PM).
                    </p>
                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('portal.contact') }}" class="inline-flex items-center min-h-11 px-5 py-2.5 rounded-lg bg-cmc-navy hover:bg-cmc-blue text-white font-semibold text-sm transition">
                            Contact Assistance Desk
                        </a>
                        <a href="{{ route('portal.careers') }}" class="inline-flex items-center min-h-11 px-5 py-2.5 rounded-lg border border-slate-200 text-cmc-navy hover:bg-slate-50 font-semibold text-sm transition">
                            Join Our Team
                        </a>
                    </div>
                </div>

                {{-- RA 10173 Privacy Notice --}}
                <div class="lg:col-span-6 lg:border-l lg:border-slate-200/80 lg:pl-8 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                    <img src="{{ $npcSealUrl }}"
                         alt="National Privacy Commission Registered Seal (Data Protection Officer & Data Processing System)"
                         width="564" height="1024" loading="lazy"
                         class="h-20 w-auto shrink-0">
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-cmc-navy text-balance">Data Privacy Act of 2012 (RA 10173)</h3>
                        <p class="text-sm text-slate-600 leading-relaxed max-w-md text-pretty">
                            NPC DPO/DPS Registered. This public portal stays strictly separated from internal clinical medical records.
                        </p>
                        <a href="{{ route('portal.privacy') }}" class="inline-flex items-center min-h-11 text-sm font-semibold text-cmc-blue hover:text-cmc-navy underline underline-offset-4 transition">
                            Read Privacy Notice &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
