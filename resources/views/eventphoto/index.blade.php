@extends('layouts.mobile-card')

@section('drawer_links')
    <a href="{{ route('home') }}"
        class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
               dark:text-gray-300 dark:hover:bg-white/[0.06]">Home</a>

    @auth
        <a href="{{ route('dashboard') }}"
            class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                   dark:text-gray-300 dark:hover:bg-white/[0.06]">Dashboard</a>
    @endauth
@endsection

@section('content_mobile')
    {{-- Title + search (FE only) --}}
    <div class="flex items-end justify-between gap-3">
        <div>
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">Event List</h1>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pesan foto kamu sekarang</p>
        </div>

        <div class="w-40">
            <input type="text" placeholder="Search..."
                class="h-9 w-full rounded-xl border border-gray-200 bg-white px-3 text-xs text-gray-700
                       focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                       dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
        </div>
    </div>

    @if (session('success'))
        <div
            class="mt-3 rounded-xl border border-green-200 bg-green-50 px-3 py-2 text-xs text-green-700
                   dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Cards --}}
    <div class="mt-4 space-y-3">
        @forelse ($eventPhotos as $eventPhoto)
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs transition
                       hover:-translate-y-0.5 hover:shadow-theme-sm
                       dark:border-gray-800 dark:bg-white/[0.03]">

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

                        <div class="mt-3 flex justify-end">
                            @if (($eventPhoto->status ?? 'open') === 'open')
                                <a href="{{ route('eventphoto.order.create', $eventPhoto) }}"
                                    class="inline-flex items-center justify-center rounded-md bg-brand-600 px-3 py-2 text-xs font-medium text-white hover:bg-brand-700">
                                    Pesan
                                </a>
                            @else
                                <span
                                    class="inline-flex items-center justify-center rounded-md bg-gray-200 px-3 py-2 text-xs font-medium text-gray-600
                                           dark:bg-gray-800 dark:text-gray-300">
                                    Closed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 text-center text-xs text-gray-500 dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-400">
                Belum ada event.
            </div>
        @endforelse
    </div>
@endsection

@section('footer_mobile')
    <a href="https://www.instagram.com/rnbh.id/" target="_blank">
        <div class="flex items-center justify-center gap-2">
            <span class="text-sm text-gray-700 hover:text-brand-600 dark:text-gray-300">rnbh.id</span>
        </div>
    </a>
@endsection
