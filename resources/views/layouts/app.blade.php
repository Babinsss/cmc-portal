@php
    $hospitalName = $settings['hospital_name'] ?? 'Capiz Medical Center';
    $tagline = $settings['tagline'] ?? 'Pag-atipan nga may gugma';
    $hotline1 = $settings['hotline_1'] ?? '(036) 621-0030';
    $hotline2 = $settings['hotline_2'] ?? '(036) 621-0823';
    $hotline1Tel = preg_replace('/[^0-9+]/', '', $hotline1);
    $hotline2Tel = preg_replace('/[^0-9+]/', '', $hotline2);
    $npcSealUrl = \App\Models\SiteSetting::resolveAssetUrl('npc_seal_path', 'images/npc-seal-official.jpg');
    $officialLogoUrl = file_exists(public_path('images/cmc-logo-official.jpg'))
        ? asset('images/cmc-logo-official.jpg')
        : asset('images/cmc-logo.svg');
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $hospitalName . ' — Official Portal')</title>
    <meta name="description" content="@yield('meta_description', $hospitalName . ' — ' . $tagline . '. Level II Government Hospital in Sitio Nasagud, Brgy. Lanot, Roxas City, Capiz.')">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/cmc-favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/cmc-logo-official.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 antialiased font-sans">
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:rounded-lg focus:bg-cmc-navy focus:text-white focus:text-sm focus:font-semibold focus:shadow-lg">
        Skip to main content
    </a>

    {{-- Sticky Hotline Bar & Primary Navigation --}}
    <div class="sticky top-0 z-40 no-print shadow-sm">
        <div class="bg-cmc-navy text-white text-xs sm:text-sm border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-0.5 flex flex-wrap items-center justify-between gap-x-4">
                <div class="flex flex-wrap items-center gap-x-1.5">
                    <span class="inline-flex items-center gap-1.5 font-semibold text-white py-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-cmc-red ring-2 ring-white/80 shrink-0"></span>
                        <span class="hidden sm:inline">24/7 Emergency &amp; Trunklines:</span>
                        <span class="sm:hidden">24/7:</span>
                    </span>
                    <div class="inline-flex items-center gap-1 tabular-nums">
                        <a href="tel:{{ $hotline1Tel }}" class="inline-flex items-center gap-1 min-h-11 px-1.5 font-semibold hover:text-teal-200 transition whitespace-nowrap">
                            <svg class="w-3.5 h-3.5 shrink-0 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            {{ $hotline1 }}
                        </a>
                        <span class="text-white/35" aria-hidden="true">/</span>
                        <a href="tel:{{ $hotline2Tel }}" class="inline-flex items-center min-h-11 px-1.5 font-semibold hover:text-teal-200 transition whitespace-nowrap">
                            {{ $hotline2 }}
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-4 text-xs text-slate-200">
                    <span>{{ $settings['hospital_classification'] ?? 'Level II Government Hospital' }} &bull; {{ $settings['authorized_beds'] ?? '147' }} Authorized Beds</span>
                    <a href="{{ route('portal.privacy') }}" class="text-teal-200 hover:text-white underline underline-offset-4 transition">RA 10173 Privacy Notice</a>
                </div>
            </div>
        </div>

        <header x-data="{
                    mobileOpen: false,
                    closeMenu(restoreFocus = false) {
                        if (!this.mobileOpen) return;
                        this.mobileOpen = false;
                        if (restoreFocus) {
                            this.$nextTick(() => this.$refs.menuToggle?.focus());
                        }
                    }
                }"
                @keydown.escape.window="closeMenu(true)"
                @click.outside="closeMenu(false)"
                class="bg-white/95 backdrop-blur border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-nowrap items-center justify-between h-16 sm:h-20 gap-2 sm:gap-3">
                    <a href="{{ route('portal.home') }}" class="flex items-center gap-2.5 sm:gap-3 min-w-0 min-h-11 group">
                        <span class="h-11 w-11 sm:h-12 sm:w-12 rounded-full p-0.5 ring-1 ring-slate-200 bg-white shrink-0 block">
                            <img src="{{ $officialLogoUrl }}" alt="{{ $hospitalName }} Official Seal"
                                 width="1024" height="1024"
                                 class="w-full h-full object-contain rounded-full">
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm sm:text-lg font-bold tracking-tight text-cmc-navy truncate group-hover:text-cmc-blue transition">{{ $hospitalName }}</p>
                            <p class="text-[11px] sm:text-xs font-medium text-cmc-teal italic truncate">"{{ $tagline }}"</p>
                        </div>
                    </a>

                    <nav aria-label="Primary Navigation" class="hidden lg:flex items-center gap-1 text-sm font-medium">
                        @php
                            $navItems = [
                                ['route' => 'portal.home', 'label' => 'Home'],
                                ['route' => 'portal.services', 'label' => 'Services'],
                                ['route' => 'portal.pricelist', 'label' => 'Pricelist'],
                                ['route' => 'portal.advisories', 'label' => 'Advisories'],
                                ['route' => 'portal.contact', 'label' => 'Contact'],
                            ];
                        @endphp
                        @foreach ($navItems as $item)
                            @php $isActive = request()->routeIs($item['route']) || ($item['route'] === 'portal.advisories' && request()->routeIs('portal.advisory.show')); @endphp
                            <a href="{{ route($item['route']) }}"
                               @if ($isActive) aria-current="page" @endif
                               class="px-3.5 py-2.5 min-h-11 inline-flex items-center rounded-lg transition {{ $isActive ? 'bg-cmc-navy text-white font-semibold shadow-xs' : 'text-slate-700 hover:bg-slate-100 hover:text-cmc-navy' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                        <a href="{{ route('portal.pricelist') }}" class="inline-flex lg:hidden items-center min-h-11 px-3 py-2 rounded-lg bg-cmc-teal text-white text-xs font-semibold hover:bg-cmc-blue transition whitespace-nowrap">
                            Pricelist
                        </a>
                        <button type="button"
                                x-ref="menuToggle"
                                @click="mobileOpen = !mobileOpen"
                                :aria-expanded="mobileOpen.toString()"
                                aria-controls="mobile-portal-menu"
                                aria-label="Toggle navigation menu"
                                class="lg:hidden inline-flex items-center justify-center min-h-11 min-w-11 p-2 rounded-lg text-slate-700 hover:bg-slate-100 hover:text-cmc-navy transition">
                            <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                            <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-portal-menu" x-show="mobileOpen" x-cloak x-transition class="lg:hidden border-t border-slate-200 bg-white">
                <nav aria-label="Mobile Navigation" class="px-4 py-3 space-y-1">
                    @foreach ($navItems as $item)
                        @php $isActive = request()->routeIs($item['route']) || ($item['route'] === 'portal.advisories' && request()->routeIs('portal.advisory.show')); @endphp
                        <a href="{{ route($item['route']) }}"
                           @if ($isActive) aria-current="page" @endif
                           class="flex items-center min-h-11 px-3.5 py-2.5 rounded-lg text-sm font-medium {{ $isActive ? 'bg-cmc-navy text-white font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <div class="pt-2 mt-2 border-t border-slate-100 grid grid-cols-2 gap-2">
                        <a href="{{ route('portal.about') }}"
                           @if (request()->routeIs('portal.about')) aria-current="page" @endif
                           class="flex items-center min-h-11 px-3.5 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.about') ? 'bg-cmc-navy text-white' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                            About CMC
                        </a>
                        <a href="{{ route('portal.careers') }}"
                           @if (request()->routeIs('portal.careers')) aria-current="page" @endif
                           class="flex items-center min-h-11 px-3.5 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('portal.careers') ? 'bg-cmc-navy text-white' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                            Careers
                        </a>
                    </div>
                </nav>
            </div>
        </header>
    </div>

    {{-- Main Page Content --}}
    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-cmc-navy text-slate-200 no-print border-t-4 border-cmc-teal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="h-12 w-12 rounded-full p-0.5 ring-2 ring-cmc-gold/70 bg-white shrink-0 block">
                            <img src="{{ $officialLogoUrl }}" alt="{{ $hospitalName }} Official Seal"
                                 width="1024" height="1024" loading="lazy"
                                 class="w-full h-full object-contain rounded-full">
                        </span>
                        <div>
                            <h3 class="font-bold text-white text-base">{{ $hospitalName }}</h3>
                            <p class="text-xs text-teal-200 italic">"{{ $tagline }}"</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        A {{ $settings['hospital_classification'] ?? 'Level II Government Hospital' }} regulated by the Department of Health (DOH) and PhilHealth.
                    </p>
                    <p class="text-xs text-slate-300">
                        <span class="font-semibold text-white">Authorized Capacity:</span> {{ $settings['authorized_beds'] ?? '147' }} Beds
                    </p>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li><a href="{{ route('portal.services') }}" class="hover:text-teal-200 transition">Medical Services Directory</a></li>
                        <li><a href="{{ route('portal.pricelist') }}" class="hover:text-teal-200 transition">Citizen's Charter Pricelist</a></li>
                        <li><a href="{{ route('portal.about') }}" class="hover:text-teal-200 transition">Vision, Mission &amp; Goals</a></li>
                        <li><a href="{{ route('portal.advisories') }}" class="hover:text-teal-200 transition">Hospital Advisories</a></li>
                        <li><a href="{{ route('portal.careers') }}" class="hover:text-teal-200 transition">Careers &amp; Vacancies</a></li>
                        <li><a href="{{ route('portal.contact') }}" class="hover:text-teal-200 transition">Contact &amp; Location</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-3">Hospital Contact</h4>
                    <ul class="space-y-2.5 text-sm text-slate-300">
                        <li class="text-pretty">{{ $settings['location'] ?? 'Sitio Nasagud, Brgy. Lanot, Roxas City, Capiz 5800, Philippines' }}</li>
                        <li class="tabular-nums">
                            <span class="text-slate-400 text-xs block">24/7 Trunklines:</span>
                            <a href="tel:{{ $hotline1Tel }}" class="hover:text-teal-200 font-medium">{{ $hotline1 }}</a>
                            &nbsp;/&nbsp;
                            <a href="tel:{{ $hotline2Tel }}" class="hover:text-teal-200 font-medium">{{ $hotline2 }}</a>
                        </li>
                        @if (! empty($settings['email']))
                            <li>
                                <span class="text-slate-400 text-xs block">Official Email:</span>
                                <a href="mailto:{{ $settings['email'] }}" class="hover:text-teal-200">{{ $settings['email'] }}</a>
                            </li>
                        @endif
                        @if (! empty($settings['facebook_url']))
                            <li>
                                <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-teal-200 hover:text-white transition">
                                    Official Facebook Page
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider">Data Privacy (RA 10173)</h4>
                    <a href="{{ route('portal.privacy') }}" class="block bg-white/95 rounded-xl p-2 shadow-sm hover:ring-2 hover:ring-teal-400 transition w-fit">
                        <img src="{{ $npcSealUrl }}" alt="NPC DPO/DPS Registered Seal" width="564" height="1024" loading="lazy" class="h-16 w-auto">
                    </a>
                    <p class="text-xs text-slate-300 leading-relaxed text-pretty">
                        Registered with the National Privacy Commission (Data Protection Officer &amp; Data Processing System · RA 10173). View our <a href="{{ route('portal.privacy') }}" class="text-teal-200 underline underline-offset-4 hover:text-white">Official Privacy Notice</a>.
                    </p>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-300">
                <p>&copy; {{ $settings['copyright_year'] ?? date('Y') }} {{ $hospitalName }}. All rights reserved.</p>
                <div class="flex flex-wrap items-center justify-center sm:justify-end gap-x-4 gap-y-2 text-center sm:text-right">
                    <span>Official Public Information Portal · Roxas City, Capiz</span>
                    <a href="{{ url('/admin') }}" class="shrink-0 whitespace-nowrap text-slate-300 hover:text-white underline underline-offset-4 transition">Staff Admin</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
