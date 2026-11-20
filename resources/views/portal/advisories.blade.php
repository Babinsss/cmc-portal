@extends('layouts.app')

@section('title', 'Hospital Advisories — ' . ($settings['hospital_name'] ?? 'Capiz Medical Center'))

@section('content')
    <section class="bg-cmc-navy text-white py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold">Hospital Advisories &amp; Public Bulletins</h1>
            <p class="text-slate-200 text-sm sm:text-base mt-2 max-w-3xl leading-relaxed">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($announcements as $advisory)
                    <article class="bg-white rounded-2xl border {{ $advisory->is_pinned ? 'border-cmc-teal ring-1 ring-cmc-teal/20' : 'border-slate-200' }} p-6 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $advisory->is_pinned ? 'bg-teal-50 text-cmc-teal border border-teal-200' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $advisory->is_pinned ? 'Pinned • ' . $advisory->category : $advisory->category }}
                                </span>
                                <time class="text-xs text-slate-500 tabular-nums">{{ ($advisory->published_at ?? $advisory->created_at)->format('M d, Y') }}</time>
                            </div>
                            <h2 class="text-lg font-bold text-cmc-navy leading-snug">
                                <a href="{{ route('portal.advisory.show', $advisory->slug) }}" class="hover:text-cmc-teal transition">
                                    {{ $advisory->title }}
                                </a>
                            </h2>
                            <p class="text-sm text-slate-600 mt-2.5 leading-relaxed">
                                {{ $advisory->summary ?: \Illuminate\Support\Str::limit(strip_tags($advisory->body), 160) }}
                            </p>
                        </div>
                        <div class="mt-5 pt-3 border-t border-slate-100">
                            <a href="{{ route('portal.advisory.show', $advisory->slug) }}" class="text-xs font-semibold text-cmc-blue hover:text-cmc-teal transition">
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
