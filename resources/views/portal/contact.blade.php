@extends('layouts.app')

@section('title', 'Contact & Location — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@php
    $hotline1 = $settings['hotline_1'] ?? '(036) 621-0030';
    $hotline2 = $settings['hotline_2'] ?? '(036) 621-0823';
    $hotline1Tel = preg_replace('/[^0-9+]/', '', $hotline1);
    $hotline2Tel = preg_replace('/[^0-9+]/', '', $hotline2);
    $campusMapUrl = \App\Models\SiteSetting::resolveAssetUrl('campus_map_path', 'images/cmc-campus-map.svg');
@endphp

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold">Contact &amp; Campus Location</h1>
            <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-3xl leading-relaxed">
                Reach our 24/7 trunklines, view our offline-ready campus access map, or send a message directly to the Capiz Medical Center assistance desk.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Column: Contact Info + Offline Campus Map --}}
            <div class="lg:col-span-6 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <h2 class="text-xl font-bold text-cmc-navy">Official Contact Channels</h2>

                    <div class="space-y-3.5 text-sm text-slate-700">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Hospital Address</p>
                            <p class="font-medium text-slate-900 mt-0.5">{{ $settings['location'] ?? 'Sitio Nasagud, Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">24/7 Trunklines</p>
                            <div class="flex flex-wrap gap-3 mt-1.5 tabular-nums">
                                <a href="tel:{{ $hotline1Tel }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-teal-50 border border-teal-200/80 text-cmc-teal font-semibold hover:bg-cmc-teal hover:text-white transition">
                                    {{ $hotline1 }}
                                </a>
                                <a href="tel:{{ $hotline2Tel }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-teal-50 border border-teal-200/80 text-cmc-teal font-semibold hover:bg-cmc-teal hover:text-white transition">
                                    {{ $hotline2 }}
                                </a>
                            </div>
                        </div>

                        @if (! empty($settings['email']))
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Official Email</p>
                                <a href="mailto:{{ $settings['email'] }}" class="text-cmc-blue font-medium hover:underline">{{ $settings['email'] }}</a>
                            </div>
                        @endif

                        @if (! empty($settings['facebook_url']))
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Official Facebook Page</p>
                                <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener noreferrer" class="text-cmc-blue font-medium hover:underline break-all">
                                    {{ $settings['facebook_url'] }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h2 class="text-lg font-bold text-cmc-navy">Campus Access Map</h2>
                            <p class="text-xs text-slate-600">Offline-ready directional guide for patients &amp; visitors</p>
                        </div>
                        @if (! empty($settings['google_maps_url']))
                            <a href="{{ $settings['google_maps_url'] }}" target="_blank" rel="noopener noreferrer"
                               class="text-xs font-semibold text-cmc-blue hover:text-cmc-teal underline underline-offset-2 shrink-0">
                                Open Google Maps &rarr;
                            </a>
                        @endif
                    </div>

                    <img src="{{ $campusMapUrl }}" alt="Capiz Medical Center Campus Access Map"
                         class="w-full rounded-xl border border-slate-200 bg-slate-50">
                </div>

                {{-- Join Our Team Callout --}}
                <div class="bg-cmc-navy text-white rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold">Join Our Healthcare Team</h2>
                        <p class="text-xs text-slate-200 mt-1 leading-relaxed">
                            View open clinical, nursing, allied health, and residency training positions at Capiz Medical Center.
                        </p>
                    </div>
                    <a href="{{ route('portal.careers') }}" class="px-4 py-2.5 rounded-lg bg-amber-400 hover:bg-amber-300 text-cmc-navy font-bold text-xs transition shrink-0 text-center">
                        View Vacancies &rarr;
                    </a>
                </div>
            </div>

            {{-- Right Column: Local Inquiry Form --}}
            <div class="lg:col-span-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-cmc-navy">Send an Inquiry</h2>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Messages are stored directly on the hospital's local assistance desk system (works 100% offline over hospital Wi-Fi &amp; kiosks).
                    </p>

                    @if (session('inquiry_success'))
                        <div role="status" class="mt-5 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-900 font-medium">
                            {{ session('inquiry_success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div role="alert" class="mt-5 rounded-xl bg-rose-50 border border-rose-200 p-4 text-xs text-rose-800 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>&bull; {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('portal.contact.submit') }}" method="POST" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Full Name <span class="text-rose-600">*</span></label>
                            <input id="name" name="name" type="text" required value="{{ old('name') }}"
                                   placeholder="Juan Dela Cruz"
                                   class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-cmc-teal focus:outline-none">
                        </div>

                        <div>
                            <label for="contact_info" class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number or Email <span class="text-rose-600">*</span></label>
                            <input id="contact_info" name="contact_info" type="text" required value="{{ old('contact_info') }}"
                                   placeholder="09XX-XXX-XXXX or email@example.com"
                                   class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-cmc-teal focus:outline-none">
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-semibold text-slate-700 mb-1">Subject / Concern <span class="text-rose-600">*</span></label>
                            <input id="subject" name="subject" type="text" required value="{{ old('subject') }}"
                                   placeholder="e.g., Outpatient Schedule / Billing / Residency Inquiry"
                                   class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-cmc-teal focus:outline-none">
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-semibold text-slate-700 mb-1">Message <span class="text-rose-600">*</span></label>
                            <textarea id="message" name="message" rows="5" required
                                      placeholder="Please do not include sensitive clinical diagnoses or patient medical records in this general inquiry form."
                                      class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:border-cmc-teal focus:outline-none">{{ old('message') }}</textarea>
                        </div>

                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="privacy_consented" value="1" {{ old('privacy_consented') ? 'checked' : '' }} required
                                       class="mt-0.5 rounded border-slate-300 text-cmc-teal focus:ring-cmc-teal">
                                <span class="text-xs text-slate-700 leading-relaxed">
                                    I consent to Capiz Medical Center processing the contact details provided above solely to respond to my inquiry, in accordance with the
                                    <a href="{{ route('portal.privacy') }}" target="_blank" rel="noopener noreferrer" class="text-cmc-blue font-semibold underline underline-offset-2">Data Privacy Act of 2012 (RA 10173)</a>.
                                </span>
                            </label>
                        </div>

                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 rounded-xl bg-cmc-navy hover:bg-cmc-blue text-white font-semibold text-sm transition cursor-pointer shadow-sm">
                            Submit Local Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
