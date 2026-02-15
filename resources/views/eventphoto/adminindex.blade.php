@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Event Photo (Admin)" />

    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3">
            <form method="GET" class="w-full max-w-sm">
                <input type="text" name="q" value="{{ $q }}" placeholder="Search title / location..."
                    class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                           focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                           dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
            </form>

            <a href="{{ route('event.eventphoto.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white
                       hover:bg-brand-700">
                + Add Event
            </a>
        </div>

        @session('success')
            <x-ui.alert variant="success">{{ $value }}</x-ui.alert>
        @endsession

        @session('error')
            <x-ui.alert variant="warning">{{ $value }}</x-ui.alert>
        @endsession


        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($events as $event)
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs
                            dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="h-32 bg-gray-100 dark:bg-gray-800">
                        @if ($event->cover_path)
                            <img src="{{ asset($event->cover_path) }}" alt="{{ $event->title }}"
                                class="h-32 w-full object-cover">
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

                        <div class="mt-4 flex items-center justify-end gap-2">
                            <a href="{{ route('event.eventphoto.edit', $event) }}"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50
                                       dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.06]">
                                Edit
                            </a>
                            <a href="{{ route('event.eventphoto.show', $event) }}"
                                class="rounded-lg bg-brand-600 px-3 py-2 text-xs font-medium text-white hover:bg-brand-700">
                                Manage
                            </a>
                            <form method="POST" action="{{ route('event.eventphoto.destroy', $event) }}"
                                onsubmit="return confirm('Hapus event ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="rounded-lg  bg-red-50 px-4 py-2 text-xs font-medium text-red-700 hover:bg-red-100
                                   dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
                                    Delete
                                </button>
                            </form>
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
