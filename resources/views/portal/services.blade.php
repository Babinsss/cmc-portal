@extends('layouts.app')

@section('title', 'Medical Services Directory — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@php
    $servicesPayload = $services->map(fn ($s) => [
        'id' => $s->id,
        'code' => $s->code,
        'name' => $s->name,
        'slug' => $s->slug,
        'schedule_label' => $s->schedule_label,
        'is_24_7' => (bool) $s->is_24_7,
        'is_highlighted' => (bool) $s->is_highlighted,
        'description' => $s->description ?? '',
        'subspecialties' => $s->subspecialties->map(fn ($sub) => [
            'name' => $sub->name,
            'schedule_note' => $sub->schedule_note,
        ])->values(),
        'search_blob' => strtolower($s->code . ' ' . $s->name . ' ' . $s->description . ' ' . $s->subspecialties->pluck('name')->implode(' ')),
    ])->values();
@endphp

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold">Medical Services &amp; Departments</h1>
            <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-3xl leading-relaxed">
                Browse Capiz Medical Center's 24/7 inpatient &amp; emergency divisions, accredited residency training, Out-Patient Department (Mon–Fri, 8:00 AM–4:00 PM), and Gugma para sa Capiz Dialysis Center.
            </p>
        </div>
    </section>

    <section x-data="{
        query: '',
        scheduleFilter: 'all',
        services: @js($servicesPayload),
        get filtered() {
            const q = this.query.trim().toLowerCase();
            return this.services.filter(s => {
                if (this.scheduleFilter === '24_7' && !s.is_24_7) return false;
                if (this.scheduleFilter === 'office' && s.is_24_7) return false;
                if (q === '') return true;
                return s.search_blob.includes(q);
            });
        }
    }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Search & Schedule Filter Bar --}}
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm mb-8 flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
            <div class="relative flex-1">
                <label for="services-search" class="sr-only">Filter medical services</label>
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                <input id="services-search" type="text" x-model="query" placeholder="Filter services or subspecialties (e.g. Cardiology, Neonatology, CT Scan)..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-cmc-teal focus:outline-none">
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="scheduleFilter = 'all'"
                        :class="scheduleFilter === 'all' ? 'bg-cmc-navy text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-3.5 py-2 rounded-lg text-xs font-semibold transition cursor-pointer tabular-nums">
                    All Divisions (<span x-text="services.length"></span>)
                </button>
                <button type="button" @click="scheduleFilter = '24_7'"
                        :class="scheduleFilter === '24_7' ? 'bg-emerald-700 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100'"
                        class="px-3.5 py-2 rounded-lg text-xs font-semibold transition cursor-pointer">
                    24/7 Emergency &amp; Inpatient
                </button>
                <button type="button" @click="scheduleFilter = 'office'"
                        :class="scheduleFilter === 'office' ? 'bg-amber-700 text-white' : 'bg-amber-50 text-amber-900 hover:bg-amber-100'"
                        class="px-3.5 py-2 rounded-lg text-xs font-semibold transition cursor-pointer">
                    OPD &amp; Specialty Clinics
                </button>
            </div>
        </div>

        {{-- Empty State --}}
        <div x-show="filtered.length === 0" x-cloak class="bg-white rounded-xl border border-slate-200 p-10 text-center">
            <p class="text-base font-semibold text-cmc-navy">No matching medical services found.</p>
            <p class="text-sm text-slate-600 mt-1">Try clearing the filter or searching another clinical specialty.</p>
            <button type="button" @click="query = ''; scheduleFilter = 'all'" class="mt-4 px-4 py-2 rounded-lg bg-cmc-teal text-white text-xs font-semibold cursor-pointer hover:bg-teal-800 transition">
                Reset Filters
            </button>
        </div>

        {{-- Service Cards Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <template x-for="service in filtered" :key="service.id">
                <article :class="service.is_highlighted ? 'border-cmc-teal ring-1 ring-cmc-teal/20' : 'border-slate-200'"
                         class="bg-white rounded-2xl border p-6 shadow-sm flex flex-col justify-between transition hover:shadow-md">
                    <div>
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-md bg-cmc-navy text-white text-xs font-bold" x-text="'Hospital Service ' + service.code"></span>
                                    <template x-if="service.is_highlighted">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-amber-100 text-amber-900 text-xs font-bold"
                                              x-text="service.slug === 'physician-residency-training-program' ? 'PCP & PSGS Accredited' : 'Core Public Service'">
                                        </span>
                                    </template>
                                </div>
                                <h2 class="text-xl font-bold text-cmc-navy mt-2 leading-snug" x-text="service.name"></h2>
                            </div>

                            <span :class="service.is_24_7 ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-900 border-amber-200'"
                                  class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border shrink-0">
                                <span :class="service.is_24_7 ? 'bg-emerald-600' : 'bg-amber-600'" class="w-2 h-2 rounded-full"></span>
                                <span x-text="service.schedule_label"></span>
                            </span>
                        </div>

                        <p x-show="service.description" class="text-sm text-slate-600 mt-3 leading-relaxed" x-text="service.description"></p>

                        <template x-if="service.subspecialties.length > 0">
                            <div class="mt-5 pt-4 border-t border-slate-100">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2.5">Units &amp; Subspecialties</h3>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <template x-for="sub in service.subspecialties" :key="sub.name">
                                        <li class="flex flex-col justify-center gap-1 px-3 py-2.5 rounded-lg bg-slate-50 border border-slate-200/70 text-xs text-slate-700">
                                            <span class="flex items-start gap-2 font-medium">
                                                <span class="w-1.5 h-1.5 rounded-full bg-cmc-teal shrink-0 mt-1.5"></span>
                                                <span x-text="sub.name"></span>
                                            </span>
                                            <template x-if="sub.schedule_note">
                                                <span class="ml-3.5 text-[11px] font-semibold text-cmc-blue" x-text="sub.schedule_note"></span>
                                            </template>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>

                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold">
                        <a href="{{ route('portal.pricelist') }}" class="text-cmc-teal hover:text-cmc-navy transition">
                            Check Citizen's Charter Rates &rarr;
                        </a>
                        <a href="{{ route('portal.contact') }}" class="text-cmc-blue hover:text-cmc-teal transition">
                            Schedule Inquiry &rarr;
                        </a>
                    </div>
                </article>
            </template>
        </div>
    </section>
@endsection
