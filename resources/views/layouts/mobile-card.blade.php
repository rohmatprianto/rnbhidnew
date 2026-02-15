@extends('layouts.fullscreen-layout')

@section('content')
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen px-4 pt-4 pb-20">
            <div class="mx-auto w-full max-w-md">
                <div class="mx-auto w-full max-w-md min-h-[calc(100vh-2rem)] rounded-3xl border-2 border-gray-200 bg-white shadow-theme-sm
             dark:border-gray-800 dark:bg-white/[0.03] flex flex-col"
                    x-data="{ openMenu: false }">
                    {{-- HEADER (FIX / STICKY) --}}
                    <header
                        class="sticky top-0 z-30 border-b border-gray-200 bg-white/95 p-5 backdrop-blur rounded-t-3xl
                           dark:border-gray-800 dark:bg-gray-900/80">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <div class="text-gray-900 dark:text-white">
                                    @include('partials.mobile.logo')
                                </div>

                            </a>

                            {{-- Burger --}}
                            <button type="button" @click="openMenu = true"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200
                                   bg-white text-gray-700 shadow-theme-xs hover:bg-gray-50
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.06]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </header>

                    {{-- Drawer --}}
                    @include('partials.mobile.drawer')

                    {{-- CONTENT (SCROLL AREA) --}}
                    <main class="overflow-y-auto p-5">
                        @yield('content_mobile')
                    </main>

                    <footer
                        class="mt-auto shrink-0 rounded-b-3xl border-t border-gray-200 bg-white/95 px-4 py-2 backdrop-blur
               dark:border-gray-800 dark:bg-gray-900/80">
                        @hasSection('footer_mobile')
                            @yield('footer_mobile')
                        @else
                            <div
                                class="flex items-center justify-center text-[11px] leading-4 text-gray-500 dark:text-gray-400">
                                © {{ date('Y') }} rnbh.id
                            </div>
                        @endif
                    </footer>

                </div>
            </div>
        </div>

        {{-- Theme toggler --}}
        @include('partials.mobile.theme-toggler')
    </div>
@endsection
