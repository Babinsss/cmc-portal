@extends('layouts.app')

@section('title', $announcement->title . ' — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('portal.advisories') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-cmc-blue hover:text-cmc-teal mb-6">
            &larr; Back to all advisories
        </a>

        <article class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-10 shadow-sm">
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if ($announcement->is_pinned)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 text-cmc-teal border border-teal-200">Pinned Advisory</span>
                @endif
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">{{ $announcement->category }}</span>
                <span class="text-xs text-slate-500 ml-auto tabular-nums">
                    Published {{ ($announcement->published_at ?? $announcement->created_at)->format('F d, Y') }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-cmc-navy leading-tight">{{ $announcement->title }}</h1>

            @if ($announcement->summary)
                <p class="mt-5 rounded-xl bg-teal-50/60 border border-cmc-teal/25 p-4 text-sm sm:text-base text-slate-700 font-medium leading-relaxed">
                    {{ $announcement->summary }}
                </p>
            @endif

            <div class="mt-6 pt-6 border-t border-slate-200 text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $announcement->body }}
            </div>
        </article>
    </section>
@endsection
