@extends('layouts.app')

@section('title', 'Careers & Vacancies — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@php
    $jobsPayload = $jobs->map(fn ($job) => [
        'id' => $job->id,
        'title' => $job->title,
        'department' => $job->department,
        'employment_type' => $job->employment_type,
        'salary_grade' => $job->salary_grade,
        'vacancies_count' => $job->vacancies_count,
        'deadline' => $job->deadline ? $job->deadline->format('M d, Y') : 'Open until filled',
        'description' => $job->description,
        'qualifications' => $job->qualifications,
    ])->values();
@endphp

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold">Careers &amp; Hospital Vacancies</h1>
            <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-3xl leading-relaxed">
                Serve the people of Capiz with integrity and compassion. View current open positions and residency training opportunities at Capiz Medical Center.
            </p>
        </div>
    </section>

    <section x-data="{
        query: '',
        typeFilter: 'all',
        jobs: @js($jobsPayload),
        get types() {
            return [...new Set(this.jobs.map(j => j.employment_type))];
        },
        get filtered() {
            const q = this.query.trim().toLowerCase();
            return this.jobs.filter(j => {
                if (this.typeFilter !== 'all' && j.employment_type !== this.typeFilter) return false;
                if (q === '') return true;
                return (j.title + ' ' + j.department + ' ' + j.description).toLowerCase().includes(q);
            });
        }
    }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm mb-8 flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
            <div class="relative flex-1">
                <label for="careers-search" class="sr-only">Search job title or department</label>
                <input id="careers-search" type="text" x-model="query" placeholder="Search job title or department..."
                       class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:border-cmc-blue">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="typeFilter = 'all'"
                        :class="typeFilter === 'all' ? 'bg-cmc-navy text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-3.5 py-2 rounded-lg text-xs font-semibold cursor-pointer transition tabular-nums">
                    All Open Roles (<span x-text="jobs.length"></span>)
                </button>
                <template x-for="type in types" :key="type">
                    <button type="button" @click="typeFilter = type"
                            :class="typeFilter === type ? 'bg-cmc-blue text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="px-3.5 py-2 rounded-lg text-xs font-semibold cursor-pointer transition"
                            x-text="type"></button>
                </template>
            </div>
        </div>

        <div x-show="filtered.length === 0" x-cloak class="bg-white rounded-2xl border border-slate-200/80 p-10 text-center text-slate-600">
            No open positions match your current filter. Please check back or inquire with our Human Resource Management Section.
        </div>

        <div class="space-y-5">
            <template x-for="job in filtered" :key="job.id">
                <article x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-cmc-blue border border-slate-200/80" x-text="job.employment_type"></span>
                                <template x-if="job.salary_grade">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 tabular-nums" x-text="job.salary_grade"></span>
                                </template>
                                <span class="text-xs text-slate-600 tabular-nums" x-text="job.vacancies_count + (job.vacancies_count === 1 ? ' Slot' : ' Slots')"></span>
                            </div>
                            <h2 class="text-xl font-bold text-cmc-navy" x-text="job.title"></h2>
                            <p class="text-sm font-medium text-slate-600 mt-0.5" x-text="job.department"></p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            <div class="text-xs text-slate-600 tabular-nums">
                                Deadline: <span class="font-semibold text-slate-900" x-text="job.deadline"></span>
                            </div>
                            <button type="button" @click="open = !open" :aria-expanded="open.toString()"
                                    class="px-4 py-2 rounded-lg bg-cmc-navy text-white text-xs font-semibold hover:bg-cmc-blue transition cursor-pointer">
                                <span x-text="open ? 'Hide Details' : 'View Qualifications'"></span>
                            </button>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 mt-3 leading-relaxed" x-text="job.description"></p>

                    <div x-show="open" x-cloak x-transition class="mt-5 pt-5 border-t border-slate-100 space-y-4">
                        <template x-if="job.qualifications">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Minimum Qualifications &amp; Requirements</h3>
                                <div class="text-sm text-slate-700 whitespace-pre-line bg-slate-50 rounded-xl p-4 border border-slate-200/70 leading-relaxed" x-text="job.qualifications"></div>
                            </div>
                        </template>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-sky-50/60 border border-slate-200/80 rounded-xl p-4 text-xs text-slate-700">
                            <span>
                                Submit application letter, PDS (CS Form 212), and PRC credentials to HR Management Office, Capiz Medical Center,
                                <a href="{{ $settings['google_maps_url'] ?? 'https://www.google.com/maps/search/?api=1&query=Roxas+Memorial+Provincial+Hospital+Outpatient+Department,+Brgy.+Lanot,+Roxas+City,+Capiz' }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-cmc-blue hover:text-cmc-navy underline underline-offset-2">{{ $settings['location'] ?? 'Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }}</a>.
                            </span>
                            <a href="{{ route('portal.contact') }}" class="font-semibold text-cmc-blue hover:underline shrink-0">Inquire via Assistance Desk &rarr;</a>
                        </div>
                    </div>
                </article>
            </template>
        </div>
    </section>
@endsection
