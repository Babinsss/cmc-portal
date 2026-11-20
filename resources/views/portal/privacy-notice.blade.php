@extends('layouts.app')

@section('title', 'Data Privacy Notice (RA 10173) — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@php
    $npcSealUrl = \App\Models\SiteSetting::resolveAssetUrl('npc_seal_path', 'images/npc-seal-official.jpg');
@endphp

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold">Official Data Privacy Notice (RA 10173)</h1>
            <p class="text-slate-200 text-sm sm:text-base mt-2 leading-relaxed">
                Capiz Medical Center's commitment to safeguarding personal information in compliance with the Philippine Data Privacy Act of 2012.
            </p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center gap-6">
            <img src="{{ $npcSealUrl }}" alt="NPC DPO/DPS Registered Seal" class="h-24 w-auto shrink-0">
            <div>
                <h2 class="text-lg font-bold text-cmc-navy">National Privacy Commission (NPC) Registration</h2>
                <p class="text-sm text-slate-600 mt-1">
                    Status: <span class="font-semibold text-cmc-teal">Registered Data Protection Officer &amp; Data Processing System (DPO/DPS) — RA 10173 Compliant</span>
                </p>
                <p class="text-xs text-slate-600 mt-1">
                    Data Protection Officer (DPO) Contact:
                    <a href="mailto:{{ $settings['dpo_email'] ?? 'dpo@capizmedicalcenter.gov.ph' }}" class="text-cmc-blue underline underline-offset-2">
                        {{ $settings['dpo_email'] ?? 'dpo@capizmedicalcenter.gov.ph' }}
                    </a>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-5 text-sm text-slate-700 leading-relaxed">
            <div>
                <h3 class="text-base font-bold text-cmc-navy">1. Strict Isolation from Clinical Patient Records</h3>
                <p class="mt-1">
                    This public information portal operates on an isolated database dedicated solely to public hospital directories, Citizen's Charter fee schedules, advisories, job postings, and general assistance desk inquiries. It is never connected to internal clinical patient medical records or Electronic Medical Records (EMR).
                </p>
            </div>

            <div>
                <h3 class="text-base font-bold text-cmc-navy">2. Information Collected via the Inquiry Form</h3>
                <p class="mt-1">
                    When you submit an inquiry on the Contact page, we collect only your name, contact information (mobile number or email), subject, and message solely for addressing your hospital inquiry or feedback. Please avoid submitting sensitive medical histories or laboratory results through this general inquiry form.
                </p>
            </div>

            <div>
                <h3 class="text-base font-bold text-cmc-navy">3. Storage, Access &amp; Retention</h3>
                <p class="mt-1">
                    Inquiry records are stored locally on Capiz Medical Center's secured intranet database, accessible only to authorized Public Information and ICT staff via role-based authentication. Records are retained only as long as necessary to resolve your request or comply with government record-keeping standards.
                </p>
            </div>

            <div>
                <h3 class="text-base font-bold text-cmc-navy">4. Your Rights as a Data Subject</h3>
                <p class="mt-1">
                    Under RA 10173, you have the right to be informed, to access, to correct, or to request deletion of your personal contact inquiry data. To exercise these rights, please reach out to our Data Protection Officer at
                    <span class="font-semibold text-cmc-navy">{{ $settings['dpo_email'] ?? 'dpo@capizmedicalcenter.gov.ph' }}</span>
                    or call <span class="font-semibold text-cmc-navy">{{ $settings['hotline_1'] ?? '(036) 621-0030' }}</span>.
                </p>
            </div>
        </div>
    </section>
@endsection
