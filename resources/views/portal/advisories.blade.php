@extends('layouts.app')

@section('title', 'Hospital Advisories — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-balance">Hospital Advisories &amp; Public Bulletins</h1>
            <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-xl leading-relaxed text-pretty">
                Official announcements, public health updates, clinic schedule reminders, and residency training notices from Capiz Medical Center.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if ($announcements->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center text-slate-600">
                No active hospital advisories are currently published.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($announcements as $advisory)
                    @php
                        $posterUrl = $advisory->imageUrl();
                    @endphp
                    <article class="bg-white rounded-2xl border border-slate-200/80 hover:border-cmc-blue/50 overflow-hidden shadow-sm flex flex-col justify-between transition">
                        <div>
                            @if ($posterUrl)
                                <a href="{{ route('portal.advisory.show', $advisory->slug) }}" class="block border-b border-slate-100 bg-slate-50">
                                    <img src="{{ $posterUrl }}"
                                         alt="{{ $advisory->title }}"
                                         loading="lazy"
                                         class="w-full h-52 object-cover object-top">
                                </a>
                            @endif
                            <div class="p-6 pb-0">
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $advisory->is_pinned ? 'bg-amber-50 text-amber-900 border border-amber-200/80' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $advisory->is_pinned ? 'Pinned · ' . $advisory->category : $advisory->category }}
                                    </span>
                                    <time class="text-xs text-slate-600 tabular-nums">{{ ($advisory->published_at ?? $advisory->created_at)->format('M d, Y') }}</time>
                                </div>
                                <h2 class="text-lg font-bold text-cmc-navy leading-snug text-balance">
                                    <a href="{{ route('portal.advisory.show', $advisory->slug) }}" class="hover:text-cmc-blue transition">
                                        {{ $advisory->title }}
                                    </a>
                                </h2>
                                <p class="text-sm text-slate-600 mt-2.5 line-clamp-2 leading-relaxed text-pretty">
                                    {{ $advisory->summary ?: \Illuminate\Support\Str::limit(strip_tags($advisory->body), 160) }}
                                </p>
                            </div>
                        </div>
                        <div class="m-6 mt-5 pt-3 border-t border-slate-100">
                            <a href="{{ route('portal.advisory.show', $advisory->slug) }}" class="inline-flex items-center min-h-11 text-xs font-semibold text-cmc-blue hover:text-cmc-navy transition">
                                Read full advisory &rarr;
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $announcements->links() }}
            </div>
        @endif
    </section>
@endsection
