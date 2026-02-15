@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Manage Booking (Admin)" />

    <div class="space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="w-full md:max-w-md">
                <input type="text" name="q" value="{{ $q }}" placeholder="Search event title / location..."
                    class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                           focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                           dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
            </form>
        </div>

        @if (session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($events as $event)
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs
                            dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="h-32 bg-gray-100 dark:bg-gray-800">
                        @if ($event->cover_path)
                            <div class="relative">
                                <img src="{{ asset($event->cover_path) }}" alt="{{ $event->title }}"
                                    class="h-32 w-full object-cover">
                                @php
                                    $count = (int) ($event->orders_count ?? 0);

                                    $badgeorder =
                                        $count > 0 ? 'bg-green-200 text-green-700' : 'bg-red-100 text-gray-700';
                                @endphp
                                <div
                                    class="absolute top-2 right-2 text-xs {{ $badgeorder }} rounded-full px-2 py-1 font-semibold">
                                    {{ $count }} {{ Str::plural('Order', $count) }}
                                </div>

                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $event->title }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $event->location ?? '-' }}
                                    •
                                    {{ $event->event_date?->format('d M Y') ?? '-' }}
                                </p>
                            </div>

                            @php
                                $s = strtolower($event->status ?? 'open');
                                $badge = match ($s) {
                                    'open' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-200',
                                    'closed' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-200',
                                    'draft' => 'bg-gray-100 text-gray-700 dark:bg-white/[0.06] dark:text-gray-300',
                                    default => 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-200',
                                };
                            @endphp

                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badge }}">
                                {{ strtoupper($event->status) }}
                            </span>
                        </div>


                        @php
                            $items = preg_split('/\s*;\s*/', trim($event->price_notes ?? ''));
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
                        <div class="flex items-center justify-end gap-2 mt-4">
                            <a href="{{ route('eventphoto.bookings.show', $event) }}"
                                class="mt-3 inline-flex items-center justify-center rounded-lg bg-brand-600 px-3 py-2 text-xs font-medium text-white
                                       hover:bg-brand-700">
                                View Orders
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-600
                           dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-300">
                    No event found.
                </div>
            @endforelse
        </div>

        <div>
            {{ $events->links() }}
        </div>
    </div>
@endsection
