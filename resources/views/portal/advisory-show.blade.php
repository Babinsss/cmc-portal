@extends('layouts.app')

@section('title', $announcement->title . ' — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@section('content')
    @php
        $posterUrl = $announcement->imageUrl();
    @endphp
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('portal.advisories') }}" class="inline-flex items-center gap-1.5 min-h-11 text-xs font-semibold text-cmc-blue hover:text-cmc-navy mb-4">
            &larr; Back to all advisories
        </a>

        <article class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-10 shadow-sm space-y-6">
            <div class="flex flex-wrap items-center gap-2">
                @if ($announcement->is_pinned)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-200/80">Pinned Advisory</span>
                @endif
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">{{ $announcement->category }}</span>
                <span class="text-xs text-slate-600 ml-auto tabular-nums">
                    Published {{ ($announcement->published_at ?? $announcement->created_at)->format('F d, Y') }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-cmc-navy leading-tight text-balance">{{ $announcement->title }}</h1>

            @if ($announcement->summary)
                <p class="text-sm sm:text-base text-slate-700 font-medium leading-relaxed max-w-2xl text-pretty">
                    {{ $announcement->summary }}
                </p>
            @endif

            <div class="pt-6 border-t border-slate-200 {{ $posterUrl ? 'grid grid-cols-1 lg:grid-cols-12 gap-8 items-start' : '' }}">
                <div class="{{ $posterUrl ? 'lg:col-span-7' : 'max-w-2xl' }} text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line text-pretty">
                    {{ $announcement->body }}
                </div>

                @if ($posterUrl)
                    <figure class="lg:col-span-5">
                        <img src="{{ $posterUrl }}"
                             alt="{{ $announcement->title }}"
                             class="w-full h-auto rounded-xl border border-slate-200 shadow-sm">
                    </figure>
                @endif
            </div>
        </article>
    </section>
@endsection
