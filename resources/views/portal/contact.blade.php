@extends('layouts.app')

@section('title', 'Contact & Location — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@php
    $hotline1 = $settings['hotline_1'] ?? '(036) 621-0030';
    $hotline2 = $settings['hotline_2'] ?? '(036) 621-0823';
    $hotline1Tel = preg_replace('/[^0-9+]/', '', $hotline1);
    $hotline2Tel = preg_replace('/[^0-9+]/', '', $hotline2);
    $campusMapUrl = \App\Models\SiteSetting::resolveAssetUrl('campus_map_path', 'images/cmc-campus-map.svg');
    $googleMapsUrl = $settings['google_maps_url'] ?? 'https://www.google.com/maps/search/?api=1&query=Roxas+Memorial+Provincial+Hospital+Outpatient+Department,+Brgy.+Lanot,+Roxas+City,+Capiz';
    $googleMapsEmbedUrl = $settings['google_maps_embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31275.168499060495!2d122.71808591083985!3d11.523420600000009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a5f485054ad937%3A0xdfe1c6fcffcd9ce2!2sRoxas%20Memorial%20Provincial%20Hospital%20Outpatient%20Department!5e0!3m2!1sen!2sph!4v1795160414285!5m2!1sen!2sph';
@endphp

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-balance">Contact &amp; Campus Location</h1>
            <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-xl leading-relaxed text-pretty">
                Reach our 24/7 trunklines, open directions to Brgy. Lanot, or send a message to the hospital desk.
            </p>
        </div>
    </section>

    <section class="bg-slate-50/70 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Left Column: Contact Info + Campus Map --}}
                <div class="lg:col-span-6 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                        <h2 class="text-xl font-bold text-cmc-navy text-balance">Official Contact Channels</h2>

                        <div class="space-y-3.5 text-sm text-slate-700">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Hospital Address (Open in Google Maps)</p>
                                <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-start gap-1.5 min-h-11 py-1 font-medium text-cmc-blue hover:text-cmc-navy underline underline-offset-4 mt-0.5 text-pretty transition">
                                    <svg class="w-4 h-4 shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    <span>{{ $settings['location'] ?? 'Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }}</span>
                                </a>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">24/7 Trunklines</p>
                                <div class="flex flex-wrap gap-3 mt-1.5 tabular-nums">
                                    <a href="tel:{{ $hotline1Tel }}" class="inline-flex items-center gap-1.5 min-h-11 px-4 py-2 rounded-lg bg-sky-50 border border-slate-200/80 text-cmc-blue font-semibold hover:bg-cmc-blue hover:text-white transition">
                                        {{ $hotline1 }}
                                    </a>
                                    <a href="tel:{{ $hotline2Tel }}" class="inline-flex items-center gap-1.5 min-h-11 px-4 py-2 rounded-lg bg-sky-50 border border-slate-200/80 text-cmc-blue font-semibold hover:bg-cmc-blue hover:text-white transition">
                                        {{ $hotline2 }}
                                    </a>
                                </div>
                            </div>

                            @if (! empty($settings['email']))
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Official Email</p>
                                    <a href="mailto:{{ $settings['email'] }}" class="inline-flex items-center min-h-11 text-cmc-blue font-medium hover:underline">{{ $settings['email'] }}</a>
                                </div>
                            @endif

                            @if (! empty($settings['facebook_url']))
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Official Facebook Page</p>
                                    <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center min-h-11 text-cmc-blue font-medium hover:underline break-all">
                                        {{ $settings['facebook_url'] }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div id="campus-map" x-data="{ mapView: 'interactive' }" class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-cmc-navy text-balance">Campus Location Map</h2>
                                <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer" class="text-xs font-medium text-cmc-blue hover:text-cmc-navy underline underline-offset-2 text-pretty">
                                    Brgy. Lanot, Roxas City, Capiz
                                </a>
                            </div>
                            <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center min-h-11 text-xs font-semibold text-cmc-blue hover:text-cmc-navy underline underline-offset-4 shrink-0">
                                Open in Google Maps &rarr;
                            </a>
                        </div>

                        <div class="flex flex-wrap items-center gap-2" role="tablist" aria-label="Map view options">
                            <button type="button"
                                    role="tab"
                                    :aria-selected="mapView === 'interactive'"
                                    @click="mapView = 'interactive'"
                                    :class="mapView === 'interactive' ? 'bg-cmc-navy text-white border-cmc-navy' : 'bg-slate-50 text-slate-700 border-slate-300 hover:bg-slate-100'"
                                    class="min-h-11 px-3.5 py-2 rounded-lg border text-xs font-semibold transition cursor-pointer">
                                Interactive Google Map
                            </button>
                            <button type="button"
                                    role="tab"
                                    :aria-selected="mapView === 'offline'"
                                    @click="mapView = 'offline'"
                                    :class="mapView === 'offline' ? 'bg-cmc-navy text-white border-cmc-navy' : 'bg-slate-50 text-slate-700 border-slate-300 hover:bg-slate-100'"
                                    class="min-h-11 px-3.5 py-2 rounded-lg border text-xs font-semibold transition cursor-pointer">
                                Offline Campus Guide
                            </button>
                        </div>

                        <div x-show="mapView === 'interactive'" class="aspect-[4/3] w-full rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
                            <iframe src="{{ $googleMapsEmbedUrl }}"
                                    title="Capiz Medical Center Location on Google Maps"
                                    class="w-full h-full border-0"
                                    allowfullscreen=""
                                    referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        </div>

                        <div x-show="mapView === 'offline'" x-cloak>
                            <img src="{{ $campusMapUrl }}"
                                 alt="Capiz Medical Center Offline Campus Access Map"
                                 loading="lazy"
                                 class="w-full rounded-xl border border-slate-200 bg-slate-50">
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed text-pretty">
                            Need offline directions inside the hospital Wi-Fi or kiosk without internet? Select <span class="font-semibold text-cmc-navy">Offline Campus Guide</span> above.
                        </p>
                    </div>

                    {{-- Join Our Team Callout --}}
                    <div class="bg-cmc-navy text-white rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-balance">Join Our Healthcare Team</h2>
                            <p class="text-xs text-slate-200 mt-1 leading-relaxed text-pretty">
                                View open clinical, nursing, allied health, and residency training positions at Capiz Medical Center.
                            </p>
                        </div>
                        <a href="{{ route('portal.careers') }}" class="inline-flex items-center justify-center min-h-11 px-4 py-2.5 rounded-lg bg-amber-400 hover:bg-amber-300 text-cmc-navy font-bold text-xs transition shrink-0 text-center">
                            View Vacancies &rarr;
                        </a>
                    </div>
                </div>

                {{-- Right Column: Local Inquiry Form (or Direct Channels in Static Mirror) --}}
                <div class="lg:col-span-6">
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                        @if (config('cmc.static_export'))
                            <h2 class="text-xl font-bold text-cmc-navy text-balance">Direct Assistance &amp; Inquiries</h2>
                            <p class="text-sm text-slate-600 mt-2 leading-relaxed text-pretty max-w-lg">
                                This online mirror provides read-only public access. For urgent triage, clinic schedules, billing verification, or official inquiries, please reach Capiz Medical Center directly through our 24/7 trunklines or official email.
                            </p>

                            <div class="mt-6 space-y-4 pt-5 border-t border-slate-200">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">24/7 Emergency &amp; Operator Trunklines</p>
                                    <div class="flex flex-wrap gap-3 mt-2 tabular-nums">
                                        <a href="tel:{{ $hotline1Tel }}" class="inline-flex items-center justify-center min-h-11 px-4 py-2.5 rounded-lg bg-cmc-navy hover:bg-cmc-blue text-white text-sm font-semibold transition">
                                            Call {{ $hotline1 }}
                                        </a>
                                        <a href="tel:{{ $hotline2Tel }}" class="inline-flex items-center justify-center min-h-11 px-4 py-2.5 rounded-lg border border-slate-300 text-cmc-navy hover:bg-slate-50 text-sm font-semibold transition">
                                            Call {{ $hotline2 }}
                                        </a>
                                    </div>
                                </div>

                                @if (! empty($settings['email']))
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Email Inquiry</p>
                                        <a href="mailto:{{ $settings['email'] }}" class="mt-2 inline-flex items-center justify-center min-h-11 px-4 py-2.5 rounded-lg border border-cmc-blue/30 bg-blue-50/70 text-cmc-blue hover:bg-cmc-blue hover:text-white text-sm font-semibold transition">
                                            Email {{ $settings['email'] }}
                                        </a>
                                    </div>
                                @endif

                                @if (! empty($settings['facebook_url']))
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Official Page</p>
                                        <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-flex items-center min-h-11 text-sm font-semibold text-cmc-blue hover:text-cmc-navy underline underline-offset-4">
                                            Message via Official Facebook Page &rarr;
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <h2 class="text-xl font-bold text-cmc-navy text-balance">Send an Inquiry</h2>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed text-pretty">
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
                                           class="w-full min-h-11 px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-cmc-blue">
                                </div>

                                <div>
                                    <label for="contact_info" class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number or Email <span class="text-rose-600">*</span></label>
                                    <input id="contact_info" name="contact_info" type="text" required value="{{ old('contact_info') }}"
                                           placeholder="09XX-XXX-XXXX or email@example.com"
                                           class="w-full min-h-11 px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-cmc-blue">
                                </div>

                                <div>
                                    <label for="subject" class="block text-xs font-semibold text-slate-700 mb-1">Subject / Concern <span class="text-rose-600">*</span></label>
                                    <input id="subject" name="subject" type="text" required value="{{ old('subject') }}"
                                           placeholder="e.g., Outpatient Schedule / Billing / Residency Inquiry"
                                           class="w-full min-h-11 px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-cmc-blue">
                                </div>

                                <div>
                                    <label for="message" class="block text-xs font-semibold text-slate-700 mb-1">Message <span class="text-rose-600">*</span></label>
                                    <textarea id="message" name="message" rows="5" required
                                              placeholder="Please do not include sensitive clinical diagnoses or patient medical records in this general inquiry form."
                                              class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-900 placeholder:text-slate-500 focus:border-cmc-blue">{{ old('message') }}</textarea>
                                </div>

                                <div class="pt-4 border-t border-slate-200">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox" name="privacy_consented" value="1" {{ old('privacy_consented') ? 'checked' : '' }} required
                                               class="mt-0.5 h-5 w-5 rounded border-slate-300 text-cmc-blue focus:ring-cmc-blue shrink-0">
                                        <span class="text-xs text-slate-700 leading-relaxed text-pretty">
                                            I consent to Capiz Medical Center processing the contact details provided above solely to respond to my inquiry, in accordance with the
                                            <a href="{{ route('portal.privacy') }}" target="_blank" rel="noopener noreferrer" class="text-cmc-blue font-semibold underline underline-offset-2">Data Privacy Act of 2012 (RA 10173)</a>.
                                        </span>
                                    </label>
                                </div>

                                <button type="submit"
                                        class="w-full sm:w-auto min-h-11 px-6 py-3 rounded-xl bg-cmc-navy hover:bg-cmc-blue text-white font-semibold text-sm transition cursor-pointer shadow-sm">
                                    Submit Local Inquiry
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
