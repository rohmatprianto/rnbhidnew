@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Booking List ({{ $eventPhoto->title }})" />

    <div class="space-y-4">
        {{-- Top bar --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="w-full md:max-w-md">
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="Search (nama/email/HP/rider/kategori)..."
                    class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                           focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                           dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
            </form>

            <a href="{{ route('eventphoto.bookings.index') }}"
                class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-200 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50
                       dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.06]">
                Back
            </a>
        </div>

        <div class="grid gap-4 grid-cols-3">
            {{-- LEFT: event info --}}
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs transition
                       hover:-translate-y-0.5 hover:shadow-theme-sm
                       dark:border-gray-800 dark:bg-white/[0.03] cols-span-1">

                <div class="flex">
                    {{-- Thumbnail / Cover --}}
                    <div class="w-32 shrink-0">
                        @php
                            $cover = $eventPhoto->cover_path ?? null; // sesuaikan nama kolom
                        @endphp

                        @if ($cover)
                            <img src="{{ asset($cover) }}" alt="{{ $eventPhoto->title ?? 'Event' }}"
                                class="h-full min-h-[110px] w-full object-cover" />
                        @else
                            <div class="h-full min-h-[110px] bg-gray-100 dark:bg-gray-800"></div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $eventPhoto->title ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $eventPhoto->location ?? '-' }}
                                    @if (!empty($eventPhoto->event_date))
                                        • {{ \Illuminate\Support\Carbon::parse($eventPhoto->event_date)->format('d M Y') }}
                                    @endif
                                </p>
                            </div>

                            @php
                                $status = $eventPhoto->status ?? 'open'; // open/closed
                                $badge =
                                    $status === 'open'
                                        ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-300'
                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                            @endphp

                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badge }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>

                        @php
                            $items = preg_split('/\s*;\s*/', trim($eventPhoto->price_notes ?? ''));
                            $items = array_values(array_filter(array_map('trim', $items)));
                        @endphp

                        @if (count($items))
                            <ul class="mt-3 list-inside list-disc space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                @foreach ($items as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-3 text-xs text-gray-600 dark:text-gray-400">—</p>
                        @endif

                    </div>
                </div>
            </div>
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 shadow-theme-xs
            dark:border-gray-800 dark:bg-white/[0.03] col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Order per Category</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Total: {{ array_sum($chartCounts ?? []) }}
                        </p>
                    </div>
                </div>

                <div class="mt-3">
                    <canvas id="ordersByCategoryChart" height="120"></canvas>
                </div>
            </div>

            {{-- Chart.js CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

            <script>
                (function() {
                    const el = document.getElementById('ordersByCategoryChart');
                    if (!el) return;

                    const labels = @json($chartLabels ?? []);
                    const data = @json($chartCounts ?? []);

                    new Chart(el, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Orders',
                                data,
                                borderWidth: 1,
                                borderRadius: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                })();
            </script>

        </div>
        @session('success')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition.opacity.duration.300ms>
                <x-ui.alert variant="success">{{ $value }}</x-ui.alert>
            </div>
        @endsession

        @session('error')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition.opacity.duration.300ms>
                <x-ui.alert variant="warning">{{ $value }}</x-ui.alert>
            </div>
        @endsession

        <div class="grid gap-4">
            {{-- LEFT: categories --}}
            <div class="">
                {{-- grid cards per category --}}
                <div class="grid gap-4 md:grid-cols-2">

                    @forelse ($categories as $category)
                        @php
                            $cat = trim($category);

                            // Ambil tahun di akhir string (contoh: "Boys 2019" -> 2019)
                            preg_match('/(\d{4})\s*$/', $cat, $m);
                            $year = $m[1] ?? 'other';

                            // Mapping warna berdasarkan tahun
                            $headerBg = match ($year) {
                                '2023' => 'bg-indigo-50 dark:bg-indigo-500/10',
                                '2022' => 'bg-sky-50 dark:bg-sky-500/10',
                                '2021' => 'bg-emerald-50 dark:bg-emerald-500/10',
                                '2020' => 'bg-amber-50 dark:bg-amber-500/10',
                                '2019' => 'bg-rose-50 dark:bg-rose-500/10',
                                '2018' => 'bg-violet-50 dark:bg-violet-500/10',
                                default => 'bg-gray-50 dark:bg-white/[0.06]',
                            };

                            $headerBorder = match ($year) {
                                '2023' => 'border-indigo-100 dark:border-indigo-500/20',
                                '2022' => 'border-sky-100 dark:border-sky-500/20',
                                '2021' => 'border-emerald-100 dark:border-emerald-500/20',
                                '2020' => 'border-amber-100 dark:border-amber-500/20',
                                '2019' => 'border-rose-100 dark:border-rose-500/20',
                                '2018' => 'border-violet-100 dark:border-violet-500/20',
                                default => 'border-gray-200 dark:border-gray-800',
                            };

                            $titleColor = match ($year) {
                                '2023' => 'text-indigo-800 dark:text-indigo-200',
                                '2022' => 'text-sky-800 dark:text-sky-200',
                                '2021' => 'text-emerald-800 dark:text-emerald-200',
                                '2020' => 'text-amber-800 dark:text-amber-200',
                                '2019' => 'text-rose-800 dark:text-rose-200',
                                '2018' => 'text-violet-800 dark:text-violet-200',
                                default => 'text-gray-900 dark:text-white',
                            };

                            $metaColor = match ($year) {
                                '2023' => 'text-indigo-700/80 dark:text-indigo-200/80',
                                '2022' => 'text-sky-700/80 dark:text-sky-200/80',
                                '2021' => 'text-emerald-700/80 dark:text-emerald-200/80',
                                '2020' => 'text-amber-700/80 dark:text-amber-200/80',
                                '2019' => 'text-rose-700/80 dark:text-rose-200/80',
                                '2018' => 'text-violet-700/80 dark:text-violet-200/80',
                                default => 'text-gray-500 dark:text-gray-400',
                            };
                        @endphp
                        @php
                            $rows = $ordersByCategory->get($category, collect());
                        @endphp

                        <div
                            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs
                                   dark:border-gray-800 dark:bg-white/[0.03]">

                            <div
                                class="flex items-center justify-between px-4 py-3 border-b {{ $headerBg }} {{ $headerBorder }}">
                                <div class="text-sm font-semibold {{ $titleColor }}">
                                    {{ $category }}
                                </div>
                                <div class="text-xs {{ $metaColor }}">
                                    Total: {{ $rows->count() }}
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="bg-gray-50 {{ $headerBg }} text-xs text-gray-600 dark:text-gray-300">
                                        <tr>
                                            <th class="px-4 py-3">Nama Rider</th>
                                            <th class="px-4 py-3">Plat No</th>
                                            <th class="px-4 py-3">Batch</th>
                                            <th class="px-4 py-3">Contact</th>
                                            <th class="px-4 py-3">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                        @foreach ($rows as $order)
                                            <tr
                                                class="border-b border-gray-100
                           odd:bg-white even:bg-gray-50 hover:bg-gray-100
                           dark:border-gray-800
                           dark:odd:bg-white/[0.02] dark:even:bg-white/[0.05] dark:hover:bg-white/[0.08]"">
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-500 dark:text-gray-400">
                                                        {{ $order->rider_full_name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        Nick : {{ $order->rider_nickname ?? '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-500 dark:text-gray-400">
                                                        {{ $order->plate_no }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-500 dark:text-gray-400">
                                                        {{ $order->batch }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-gray-500 dark:text-gray-400">
                                                        {{ $order->guardian_name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $order->phone }}
                                                    </div>
                                                </td>

                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('eventphoto.bookings.order.edit', $order) }}"
                                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                            </svg>
                                                        </a>

                                                        <form
                                                            action="{{ route('eventphoto.bookings.order.destroy', $order) }}"
                                                            method="POST" onsubmit="return confirm('Hapus booking ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="3 6 5 6 21 6" />
                                                                    <path
                                                                        d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m5 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                                                    <line x1="10" y1="11" x2="10"
                                                                        y2="17" />
                                                                    <line x1="14" y1="11" x2="14"
                                                                        y2="17" />
                                                                </svg>

                                                            </button>
                                                        </form>


                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @empty
                        <div
                            class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-600
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-300">
                            No booking found.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT: sidebar event info --}}

        </div>
    </div>
@endsection
