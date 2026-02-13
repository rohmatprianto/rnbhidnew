@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Users" />

    <div x-data="userIndex({
        initialTab: @js($tab ?? 'users'),
        counts: @js($counts ?? ['users' => 0, 'reviewusers' => 0, 'deleteusers' => 0]),
        initialQuery: @js($q ?? ''),
        endpoint: @js(route('users.index', absolute: false)),
    })">
        <!-- Tabs -->
        <div
            class="flex overflow-x-auto overflow-y-hidden border-b mb-4 border-gray-200 whitespace-nowrap dark:border-gray-700">
            <!-- users -->
            <button type="button" @click="setTab('users')" :aria-selected="tab === 'users'"
                class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center bg-transparent border-b-2 sm:px-4 -px-1 whitespace-nowrap focus:outline-none"
                :class="tab === 'users' ? 'text-blue-600 border-blue-500 dark:border-blue-400 dark:text-blue-300' :
                    'text-gray-700 border-transparent dark:text-white hover:border-gray-400'">
                <span class="mx-1 text-sm sm:text-base">Users</span>
                <span
                    class="ml-2 inline-flex items-center rounded-md bg-blue-400 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-blue-400 dark:text-gray-200"
                    x-text="counts.users"></span>
            </button>

            <!-- reviewusers -->
            <button type="button" @click="setTab('reviewusers')" :aria-selected="tab === 'reviewusers'"
                class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center bg-transparent border-b-2 sm:px-4 -px-1 whitespace-nowrap focus:outline-none"
                :class="tab === 'reviewusers' ? 'text-blue-600 border-blue-500 dark:border-blue-400 dark:text-blue-300' :
                    'text-gray-700 border-transparent dark:text-white hover:border-gray-400'">
                <span class="mx-1 text-sm sm:text-base">Review</span>
                <span
                    class="ml-2 inline-flex items-center rounded-md bg-amber-400 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-orange-400 dark:text-gray-200"
                    x-text="counts.reviewusers"></span>
            </button>

            <!-- deleteusers -->
            <button type="button" @click="setTab('deleteusers')" :aria-selected="tab === 'deleteusers'"
                class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center bg-transparent border-b-2 sm:px-4 -px-1 whitespace-nowrap focus:outline-none"
                :class="tab === 'deleteusers' ? 'text-blue-600 border-blue-500 dark:border-blue-400 dark:text-blue-300' :
                    'text-gray-700 border-transparent dark:text-white hover:border-gray-400'">
                <span class="mx-1 text-sm sm:text-base">Deleted</span>
                <span
                    class="ml-2 inline-flex items-center rounded-md bg-red-400 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-red-400 dark:text-gray-200"
                    x-text="counts.deleteusers"></span>
            </button>
        </div>

        @session('success')
            <x-ui.alert variant="success">{{ $value }}</x-ui.alert>
        @endsession

        @session('error')
            <x-ui.alert variant="warning">{{ $value }}</x-ui.alert>
        @endsession

        <!-- Search (per tab) -->
        <div class="mt-4">
            <div class="flex items-center gap-3 justify-between">
                <div class="relative w-full max-w-md">
                    <input type="text"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 shadow-theme-xs outline-none focus:border-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        placeholder="Search name, email, phone, sosmed..." x-model="q"
                        @input.debounce.350ms="reload()" />
                    <button type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        x-show="q.length" @click="q=''; reload()" aria-label="Clear">
                        ✕
                    </button>
                </div>

                <div class="text-xs text-gray-500 dark:text-gray-400" x-show="loading">
                    Loading...
                </div>
            </div>
        </div>

        <!-- Panel table -->
        <div class="pt-4">
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div x-html="tableHtml"></div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        function userIndex({
            initialTab,
            counts,
            initialQuery,
            endpoint
        }) {
            return {
                // tab & filter
                tab: initialTab || 'users',
                counts: counts || {
                    users: 0,
                    reviewusers: 0,
                    deleteusers: 0
                },
                q: initialQuery || '',

                // ajax
                endpoint,
                tableHtml: '',
                loading: false,

                // pagination state (sync dari server)
                currentPage: 1,
                totalPages: 1,
                prevUrl: '',
                nextUrl: '',

                init() {
                    this.reload();
                },

                setTab(next) {
                    this.tab = next;
                    this.q = '';
                    this.reload(); // reset ke page 1 otomatis
                },

                // computed pages with ellipsis
                get displayedPages() {
                    const total = this.totalPages;
                    const current = this.currentPage;

                    if (total <= 7) {
                        return Array.from({
                            length: total
                        }, (_, i) => i + 1);
                    }

                    const pages = [];
                    const add = (p) => pages.push(p);

                    add(1);

                    if (current > 4) add('...');

                    const start = Math.max(2, current - 1);
                    const end = Math.min(total - 1, current + 1);

                    for (let p = start; p <= end; p++) add(p);

                    if (current < total - 3) add('...');

                    add(total);

                    return pages;
                },

                prevPage() {
                    if (this.currentPage <= 1) return;
                    if (this.prevUrl) this.reload(this.prevUrl);
                },

                nextPage() {
                    if (this.currentPage >= this.totalPages) return;
                    if (this.nextUrl) this.reload(this.nextUrl);
                },

                goToPage(page) {
                    if (!page || page === '...') return;
                    if (page === this.currentPage) return;

                    const u = new URL(this.endpoint, window.location.origin);
                    u.searchParams.set('tab', this.tab);
                    if (this.q && this.q.trim().length) u.searchParams.set('q', this.q.trim());
                    u.searchParams.set('page', page);

                    this.reload(u.toString());
                },

                async reload(url = null) {
                    this.loading = true;

                    const u = url ?
                        new URL(url, window.location.origin) :
                        new URL(this.endpoint, window.location.origin);

                    u.searchParams.set('tab', this.tab);

                    // kalau search berubah, reset page ke 1 (kecuali reload dari URL pagination)
                    if (!url) u.searchParams.delete('page');

                    if (this.q && this.q.trim().length) {
                        u.searchParams.set('q', this.q.trim());
                    } else {
                        u.searchParams.delete('q');
                    }

                    const res = await fetch(u.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    this.tableHtml = await res.text();
                    this.loading = false;

                    // sync pagination meta dari partial
                    this.$nextTick(() => {
                        const meta = document.getElementById('usersTableRoot');
                        if (!meta) {
                            // fallback default
                            this.currentPage = 1;
                            this.totalPages = 1;
                            this.prevUrl = '';
                            this.nextUrl = '';
                            return;
                        }

                        this.currentPage = parseInt(meta.dataset.currentPage || '1', 10);
                        this.totalPages = parseInt(meta.dataset.totalPages || '1', 10);
                        this.prevUrl = meta.dataset.prevUrl || '';
                        this.nextUrl = meta.dataset.nextUrl || '';
                    });
                },
            }
        }
    </script>

@endsection
