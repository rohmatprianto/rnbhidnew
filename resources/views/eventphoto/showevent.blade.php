@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Event Photo Detail" />

    @session('success')
        <x-ui.alert variant="success">{{ $value }}</x-ui.alert>
    @endsession

    @session('error')
        <x-ui.alert variant="warning">{{ $value }}</x-ui.alert>
    @endsession

    <div class="max-w-xl  space-y-4 mt-4">
        <div
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs
                    dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="h-56 bg-gray-100 dark:bg-gray-800">
                @if ($event->cover_path)
                    <img src="{{ asset($event->cover_path) }}" alt="{{ $event->title }}" class="h-56 w-full object-cover">
                @endif
            </div>

            <div class="p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h1 class="truncate text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $event->title }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $event->location ?? '-' }} • {{ $event->event_date?->format('d M Y') ?? '-' }}
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

                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Price Notes</p>

                    @php
                        $items = preg_split('/\s*;\s*/', trim($event->price_notes ?? ''));
                        $items = array_values(array_filter(array_map('trim', $items)));
                    @endphp

                    @if (count($items))
                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-gray-600 dark:text-gray-300">
                            @foreach ($items as $item)
                                <li class="break-words">{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">—</p>
                    @endif
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <a href="{{ route('event.eventphoto.index') }}"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                               dark:border-gray-700 dark:text-gray-200 dark:hover:bg-white/[0.06]">
                        Back
                    </a>
                    <form method="POST" action="{{ route('event.eventphoto.destroy', $event) }}"
                        onsubmit="return confirm('Hapus event ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100
                                   dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
                            Delete
                        </button>
                    </form>
                    <a href="{{ route('event.eventphoto.edit', $event) }}"
                        class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
