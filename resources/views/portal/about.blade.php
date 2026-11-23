@extends('layouts.app')

@section('title', 'About ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@php
    $heroImageUrl = \App\Models\SiteSetting::resolveAssetUrl('hero_image_path', 'images/cmc-building.jpg');
    $officialLogoUrl = file_exists(public_path('images/cmc-logo-official.jpg'))
        ? asset('images/cmc-logo-official.jpg')
        : asset('images/cmc-logo.svg');
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
@endphp

@section('content')
    <section class="relative bg-[#0b223f] text-white py-10 sm:py-12 overflow-hidden">
        <img src="{{ $heroImageUrl }}" alt="" aria-hidden="true"
             class="absolute inset-0 w-full h-full object-cover object-bottom opacity-20 pointer-events-none select-none">
        <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-r from-[#0a2240]/95 via-[#0a2240]/90 to-[#0e4a86]/80 pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center gap-6">
            <span class="h-20 w-20 sm:h-24 sm:w-24 rounded-full p-1 ring-1 ring-white/40 bg-white shadow-md shrink-0 block">
                <img src="{{ $officialLogoUrl }}" alt="{{ $settings['hospital_name'] ?? 'Capiz Medical Center' }} Official Seal"
                     class="w-full h-full object-contain rounded-full">
            </span>
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold">About {{ $settings['hospital_name'] ?? 'Capiz Medical Center' }}</h1>
                <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-3xl leading-relaxed">
                    Level II Government Hospital serving Roxas City and the Province of Capiz with compassionate, accessible public healthcare.
                </p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        {{-- Accreditation & Bed Capacity Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-cmc-navy">{{ $settings['hospital_classification'] ?? 'Level II Government Hospital' }}</h2>
                <p class="text-xs font-semibold text-cmc-blue mt-1">Hospital Classification</p>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">Licensed by the Department of Health (DOH) and accredited by PhilHealth.</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <h2 class="text-3xl font-extrabold text-cmc-navy tabular-nums">{{ $settings['authorized_beds'] ?? '147' }} Beds</h2>
                <p class="text-xs font-semibold text-cmc-blue mt-1">Authorized Bed Capacity</p>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">General medicine, surgery, obstetrics &amp; gynecology, pediatrics, ICU/NICU, and residency training.</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <h2 class="text-xl font-extrabold text-cmc-navy italic">"{{ $settings['tagline'] ?? 'Pag-atipan nga may gugma' }}"</h2>
                <p class="text-xs font-semibold text-cmc-blue mt-1">Official Tagline</p>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">Patient-centered public service across Roxas City and the Province of Capiz.</p>
            </div>
        </div>

        {{-- Vision & Mission --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-cmc-navy to-cmc-blue text-white rounded-2xl p-8 shadow-md">
                <h2 class="text-xs font-bold uppercase tracking-wider text-sky-200 mb-4">Our Vision</h2>
                <blockquote class="text-lg sm:text-xl font-semibold leading-relaxed">
                    "{{ $settings['vision'] ?? '' }}"
                </blockquote>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
                <h2 class="text-xs font-bold uppercase tracking-wider text-cmc-blue mb-4">Our Mission Statement</h2>
                <p class="text-base sm:text-lg text-slate-700 leading-relaxed font-medium">
                    "{{ $settings['mission'] ?? '' }}"
                </p>
            </div>
        </div>

        {{-- Institutional Goals --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-cmc-navy mb-6">Institutional Goals</h2>
            <ol class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                @foreach ($goals as $index => $goal)
                    <li class="flex items-baseline gap-3.5 pt-4 border-t border-slate-100">
                        <span class="font-bold text-sm text-cmc-blue tabular-nums shrink-0">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $goal }}</p>
                    </li>
                @endforeach
            </ol>
        </div>

        {{-- Cross-Portal Navigation --}}
        <div class="bg-slate-100 rounded-2xl border border-slate-200 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <p class="text-sm font-medium text-slate-700">
                Looking for hospital charges, clinical divisions, or open staff positions?
            </p>
            <div class="flex flex-wrap gap-3 text-xs font-semibold shrink-0">
                <a href="{{ route('portal.pricelist') }}" class="px-4 py-2.5 rounded-lg bg-cmc-navy text-white hover:bg-cmc-blue transition">Citizen's Charter Pricelist</a>
                <a href="{{ route('portal.services') }}" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-cmc-navy hover:bg-slate-50 transition">Medical Services</a>
                <a href="{{ route('portal.careers') }}" class="px-4 py-2.5 rounded-lg bg-white border border-slate-300 text-cmc-navy hover:bg-slate-50 transition">Careers</a>
            </div>
        </div>
    </section>
@endsection
