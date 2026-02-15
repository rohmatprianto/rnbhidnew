@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Booking ({{ $eventPhoto->title }})" />

    <div class="space-y-4">
        <div class="flex flex-row gap-4">
            <div class="w-1/2 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="text-sm font-semibold text-gray-900 dark:text-white">Edit Order</div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ $order->guardian_name }} • {{ $order->rider_full_name }}
                </div>

                @if ($errors->any())
                    <div
                        class="mt-3 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700
                        dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-200">
                        <div class="font-semibold">Periksa input:</div>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('eventphoto.bookings.order.update', $order) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Nama Wali
                                *</label>
                            <input name="guardian_name" value="{{ old('guardian_name', $order->guardian_name) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">No HP *</label>
                            <input name="phone" value="{{ old('phone', $order->phone) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $order->email) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Instagram</label>
                            <input name="instagram" value="{{ old('instagram', $order->instagram) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Nama Rider *</label>
                        <input name="rider_full_name" value="{{ old('rider_full_name', $order->rider_full_name) }}"
                            class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                           dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200"
                            required>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Nickname
                                *</label>
                            <input name="rider_nickname" value="{{ old('rider_nickname', $order->rider_nickname) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Category
                                *</label>
                            <input name="category" value="{{ old('category', $order->category) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Plat No</label>
                            <input name="plate_no" value="{{ old('plate_no', $order->plate_no) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Batch</label>
                            <input name="batch" value="{{ old('batch', $order->batch) }}"
                                class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm
                               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <a href="{{ route('eventphoto.bookings.show', $eventPhoto->id) }}"
                            class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                          dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.06]">Cancel</a>

                        <button class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                            Save
                        </button>
                    </div>
                </form>
            </div>
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs
                            dark:border-gray-800 dark:bg-white/[0.03] h-fit">
                <div class="h-32 bg-gray-100 dark:bg-gray-800">
                    @if ($eventPhoto->cover_path)
                        <img src="{{ asset($eventPhoto->cover_path) }}" alt="{{ $eventPhoto->title }}"
                            class="h-32 w-full object-cover">
                    @endif
                </div>

                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $eventPhoto->title }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $eventPhoto->location ?? '-' }}
                                •
                                {{ $eventPhoto->event_date?->format('d M Y') ?? '-' }}
                            </p>
                        </div>

                        @php
                            $s = strtolower($eventPhoto->status ?? 'open');
                            $badge = match ($s) {
                                'open' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-200',
                                'closed' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-200',
                                'draft' => 'bg-gray-100 text-gray-700 dark:bg-white/[0.06] dark:text-gray-300',
                                default => 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-200',
                            };
                        @endphp

                        <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badge }}">
                            {{ strtoupper($eventPhoto->status) }}
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

                    <p class="mt-3 font-bold text-xs text-gray-600 dark:text-gray-400">Class Category</p>
                    @php
                        $items = preg_split('/\s*;\s*/', trim($eventPhoto->category_notes ?? ''));
                        $items = array_values(array_filter(array_map('trim', $items)));
                    @endphp

                    @if (count($items))
                        <ul
                            class="mt-3 list-disc list-inside grid grid-cols-3 gap-x-3 gap-y-1 text-xs text-gray-600 dark:text-gray-400">
                            @foreach ($items as $item)
                                <li class="break-words">{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-3 text-xs text-gray-600 dark:text-gray-400">—</p>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection
