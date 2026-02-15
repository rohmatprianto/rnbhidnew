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
    <a href="{{ route('eventphoto.index') }}"
        class="flex items-center justify-end gap-2 mb-4 text-slate-600 dark:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-arrow-left-icon lucide-arrow-left">
            <path d="m12 19-7-7 7-7" />
            <path d="M19 12H5" />
        </svg>
        <span class="text-sm text-gray-600 hover:text-brand-600 dark:text-gray-300">back</span>
    </a>
    {{-- Cards --}}
    {{-- Cards --}}
    <div class="space-y-3">
        <div
            class="block overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs transition
                hover:-translate-y-0.5 hover:shadow-theme-sm
                dark:border-gray-800 dark:bg-white/[0.08]">

            <div class="flex">
                {{-- Thumbnail / Cover --}}
                <div class="w-35 shrink-0">
                    @php
                        $cover = $eventPhoto->cover_path ?? null;
                    @endphp

                    @if ($cover)
                        <img src="{{ asset($cover) }}" alt="{{ $eventPhoto->title }}"
                            class="h-full min-h-[96px] w-full object-cover" />
                    @else
                        <div class="h-full min-h-[96px] bg-gray-100 dark:bg-gray-800"></div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $eventPhoto->title }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $eventPhoto->location ?? '-' }}
                                @if (!empty($eventPhoto->event_date))
                                    • {{ \Carbon\Carbon::parse($eventPhoto->event_date)->format('d M Y') }}
                                @endif
                            </p>
                        </div>

                        @php
                            $status = strtolower($eventPhoto->status ?? 'open');
                            $badgeClass = match ($status) {
                                'open' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-300',
                                'closed' => 'bg-gray-100 text-gray-700 dark:bg-white/10 dark:text-gray-300',
                                default => 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-300',
                            };
                        @endphp

                        <span class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badgeClass }}">
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

                    <p class="mt-3 truncate text-sm font-semibold text-rose-400">
                        Booking tidak bisa cancel
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- form pemesanan --}}
    <div
        class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 shadow-theme-xs
            dark:border-gray-800 dark:bg-white/[0.08]">
        <h2 class="text-sm font-semibold text-rose-400">Syarat dan Ketentuan</h2>
        <ul class="list-inside list-disc text-xs text-gray-600 dark:text-gray-400 mb-4">
            <li>Booking tidak bisa cancel.</li>
            <li>Pastikan data yang diinput benar dan lengkap.</li>
            @php
                $items = preg_split('/\s*;\s*/', trim($eventPhoto->price_notes ?? ''));
                $items = array_values(array_filter(array_map('trim', $items)));
            @endphp

            @if (count($items))
                @foreach ($items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            @else
                <p class="mt-3 text-xs text-gray-600 dark:text-gray-400">—</p>
            @endif
            <li>Pembayaran via transfer bank</li>
            <li>Contact Person by WA 0813111111</li>
        </ul>
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Form Booking</h2>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lengkapi data berikut untuk booking foto.</p>

        @if ($errors->any())
            <div
                class="mt-3 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700
                    dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-200">
                <div class="font-semibold">Periksa input kamu:</div>
                <ul class="mt-1 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('eventphoto.order.store', $eventPhoto) }}" method="POST" class="mt-4 space-y-3">
            @csrf

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                        Nama Wali Rider <span class="text-red-500">*</span>
                    </label>
                    <input name="guardian_name" value="{{ old('guardian_name') }}" required
                        class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                    @error('guardian_name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                            No Handphone <span class="text-red-500">*</span>
                        </label>
                        <input name="phone" value="{{ old('phone') }}" required
                            class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                        Nama Lengkap Rider <span class="text-red-500">*</span>
                    </label>
                    <input name="rider_full_name" value="{{ old('rider_full_name') }}" required
                        class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                    @error('rider_full_name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                            Nama Panggilan Rider <span class="text-red-500">*</span>
                        </label>
                        <input name="rider_nickname" value="{{ old('rider_nickname') }}" required
                            class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                        @error('rider_nickname')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">
                            Kategori / Class <span class="text-red-500">*</span>
                        </label>
                        <input name="category" value="{{ old('category') }}" required
                            class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                        @error('category')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">No Plat</label>
                        <input name="plate_number" value="{{ old('plate_number') }}"
                            class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Batch</label>
                        <input name="batch" value="{{ old('batch') }}"
                            class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Instagram</label>
                    <input name="instagram" value="{{ old('instagram') }}"
                        class="h-10 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                </div>

                <button type="submit"
                    class="mt-2 w-full rounded-xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white hover:bg-brand-700">
                    Submit Order
                </button>
            </div>
        </form>

    </div>

@endsection

@section('footer_mobile')
    <a href="https://www.instagram.com/rnbh.id/" target="_blank">
        <div class="flex items-center justify-center gap-2">
            <span class="text-sm text-gray-700 hover:text-brand-600 dark:text-gray-300">rnbh.id</span>
        </div>
    </a>
@endsection
