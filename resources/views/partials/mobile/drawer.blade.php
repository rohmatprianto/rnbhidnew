<div x-show="openMenu" x-cloak class="fixed inset-0 z-[99999]" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-black/40" @click="openMenu=false"></div>

    <aside class="absolute right-0 top-0 h-full w-72 bg-white p-5 shadow-xl dark:bg-gray-900">
        <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Menu</p>
            <button type="button" @click="openMenu=false"
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.06]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <div class="mt-4 space-y-2">
            {{-- Default (boleh override dari page) --}}
            @hasSection('drawer_links')
                @yield('drawer_links')
            @else
                <a href="{{ route('home') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                           dark:text-gray-300 dark:hover:bg-white/[0.06]">Home</a>

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                               dark:text-gray-300 dark:hover:bg-white/[0.06]">Dashboard</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50
                                   dark:text-red-400 dark:hover:bg-red-500/10">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                               dark:text-gray-300 dark:hover:bg-white/[0.06]">Sign
                        In</a>
                    <a href="{{ route('register') }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                               dark:text-gray-300 dark:hover:bg-white/[0.06]">Sign
                        Up</a>
                @endauth
            @endif
        </div>
    </aside>
</div>
