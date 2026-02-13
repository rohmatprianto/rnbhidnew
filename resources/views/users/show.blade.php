@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'User Detail'" />

    <div class="space-y-6">

        @session('success')
            <x-ui.alert variant="success">
                {{ $value }}
            </x-ui.alert>
        @endsession

        @session('error')
            <x-ui.alert variant="warning">
                {{ $value }}
            </x-ui.alert>
        @endsession

        <div class="flex flex-col gap-6 md:flex-row">
            <div class="md:w-1/3">
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    {{-- Header image / photo --}}
                    <div class="relative">
                        <img src="{{ $user->photo_path ? asset($user->photo_path) : asset('images/assets/userphoto/default.png') }}"
                            alt="{{ $user->name }}" class="h-60 w-full object-fit" />
                    </div>

                    {{-- Body --}}
                    <div class="px-6 pt-4 pb-6">
                        {{-- Title row --}}
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Role : {{ (bool) ($user->is_admin ?? false) ? 'Admin' : 'User' }}
                                </p>
                            </div>

                            {{-- Status badge --}}
                            @php
                                $status = $user->status ?? '-';
                                $badgeClass = match ($status) {
                                    'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'review' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'reject' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                                };
                            @endphp
                            @if ((bool) (auth()->user()->is_admin ?? false))
                                {{-- Status badge -> Button + Modal --}}
                                @php
                                    $status = $user->status ?? '-';
                                    $badgeClass = match ($status) {
                                        'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'review'
                                            => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'reject' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                                    };
                                @endphp

                                <div x-data="{ open: false }" class="inline-flex">
                                    {{-- Trigger --}}
                                    <button type="button" @click="open = true"
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-medium {{ $badgeClass }}
               hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                                        {{ $status }}
                                        <svg class="ml-1.5 h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>

                                    {{-- Modal --}}
                                    <div x-show="open" x-cloak
                                        class="fixed inset-0 z-[99999] flex items-center justify-center" aria-modal="true"
                                        role="dialog">
                                        {{-- Backdrop --}}
                                        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

                                        {{-- Panel --}}
                                        <div
                                            class="relative w-full max-w-sm rounded-xl bg-white p-5 shadow-xl dark:bg-gray-900">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Update
                                                        Status</h3>
                                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                        Pilih status untuk <span
                                                            class="font-medium">{{ $user->name }}</span>
                                                    </p>
                                                </div>

                                                <button type="button" @click="open=false"
                                                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/[0.06]">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="mt-4 grid grid-cols-3 gap-2">
                                                {{-- Aktif --}}
                                                <form method="POST" action="{{ route('users.status.update', $user) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="aktif">
                                                    <button type="submit"
                                                        class="w-full rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">
                                                        Aktif
                                                    </button>
                                                </form>

                                                {{-- Review --}}
                                                <form method="POST" action="{{ route('users.status.update', $user) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="review">
                                                    <button type="submit"
                                                        class="w-full rounded-lg bg-yellow-500 px-3 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                                                        Review
                                                    </button>
                                                </form>

                                                {{-- Reject --}}
                                                <form method="POST" action="{{ route('users.status.update', $user) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="reject">
                                                    <button type="submit"
                                                        class="w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="mt-4 flex justify-end">
                                                <button type="button" @click="open=false"
                                                    class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50
                           dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.06]">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-medium {{ $badgeClass }}">
                                    {{ $status }}
                                </span>
                            @endif
                        </div>
                        {{-- Divider --}}
                        <div class="my-6 h-px w-full bg-gray-200 dark:bg-gray-800"></div>

                        {{-- Fields --}}
                        <div class="space-y-4">
                            {{-- Email --}}
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    {{-- Icon Email --}}
                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</p>
                                </div>

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->email ?? '-' }}
                                </p>
                            </div>

                            {{-- Phone (WhatsApp) --}}
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    {{-- Icon WhatsApp --}}
                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 32 32"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.11 17.53c-.28-.14-1.63-.8-1.88-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.19-.32.21-.6.07-.28-.14-1.19-.44-2.27-1.39-.84-.75-1.4-1.67-1.56-1.95-.16-.28-.02-.43.12-.57.12-.12.28-.32.42-.48.14-.16.19-.28.28-.46.09-.19.05-.35-.02-.49-.07-.14-.61-1.47-.84-2.01-.22-.54-.44-.46-.61-.47h-.52c-.18 0-.46.07-.7.35-.25.28-.93.9-.93 2.2 0 1.3.95 2.55 1.09 2.72.14.19 1.87 2.85 4.52 3.99.63.27 1.12.43 1.51.55.63.2 1.21.17 1.67.1.51-.08 1.63-.67 1.86-1.31.23-.64.23-1.18.16-1.31-.07-.13-.25-.2-.53-.34Z" />
                                        <path
                                            d="M26.4 5.6A13.36 13.36 0 0 0 16.01 1.5C8.84 1.5 3 7.34 3 14.52c0 2.31.6 4.56 1.74 6.55L3 30.5l9.66-1.67a13.4 13.4 0 0 0 6.45 1.65h.01c7.18 0 13.02-5.84 13.02-13.02 0-3.48-1.36-6.75-3.74-9.16ZM19.11 28.3h-.01a11.2 11.2 0 0 1-5.7-1.56l-.41-.24-5.73.99.99-5.59-.27-.43a11.22 11.22 0 0 1-1.74-5.95C6.25 8.65 10.79 4.1 16.01 4.1c2.8 0 5.43 1.09 7.4 3.07a10.36 10.36 0 0 1 3.06 7.36c0 5.22-4.54 13.77-7.36 13.77Z"
                                            fill-rule="evenodd" clip-rule="evenodd" />
                                    </svg>

                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Phone</p>
                                </div>

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->phone ?? '-' }}
                                </p>
                            </div>

                            {{-- Sosmed (Instagram) --}}
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    {{-- Icon Instagram --}}
                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M17.5 6.5h.01" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" />
                                    </svg>

                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Instagram</p>
                                </div>

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->sosmed ?? '-' }}
                                </p>
                            </div>

                        </div>

                        {{-- Divider --}}
                        <div class="my-6 h-px w-full bg-gray-200 dark:bg-gray-800"></div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('users.index') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                Back
                            </a>


                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-2/3">
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="px-6 py-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Additional Card</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Isi card kedua di sini (mis. aktivitas user, log, role/permission, dsb).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
