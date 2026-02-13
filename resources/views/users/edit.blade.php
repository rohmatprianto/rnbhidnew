@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit User" />

    <div class="space-y-6">
        <div class="flex flex-col gap-6 md:flex-row">

            {{-- LEFT: FORM EDIT --}}
            <div class="md:w-2/3">
                <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                    <form action="{{ route('users.update', $user) }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-forms.input label="Name" name="name" :value="$user->name" required />
                        </div>

                        <div class="mt-4">
                            <x-forms.input label="Email" name="email" :value="$user->email" required />
                        </div>

                        <div class="mt-4">
                            <x-forms.input label="Phone" name="phone" :value="$user->phone" />
                        </div>

                        <div class="mt-4">
                            <x-forms.input label="Instagram" name="sosmed" :value="$user->sosmed" />
                        </div>


                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('users.index') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                Back
                            </a>
                            <div class="flex items-center gap-3">
                                <button type="submit"
                                    class="flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white hover:bg-brand-600">
                                    Update User
                                </button>

                                <a href="{{ route('users.index') }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: USER CARD + PHOTO FORM --}}
            <div class="md:w-1/3">
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Photo --}}
                    <div class="relative">
                        <img src="{{ $user->photo_path ? asset($user->photo_path) : asset('images/assets/userphoto/default.png') }}"
                            alt="{{ $user->name }}" class="h-60 w-full object-cover" />
                    </div>

                    {{-- Photo update form (SEPARATE) --}}
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                        <form action="{{ route('users.photo.update', $user) }}" method="POST" enctype="multipart/form-data"
                            class="mt-4 space-y-3">
                            @csrf
                            @method('PATCH')

                            @error('photo_path')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="grid grid-cols-1 gap-3 md:grid-cols-3 md:items-end">
                                <div class="md:col-span-2">
                                    <input type="file" name="photo_path" accept="image/*"
                                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm
                   dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                                </div>

                                <div class="md:col-span-1">
                                    <button type="submit"
                                        class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                                        Update Photo
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 pt-4 pb-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Role : {{ (bool) ($user->is_admin ?? false) ? 'Admin' : 'User' }}
                                </p>
                            </div>

                            {{-- Status badge (admin can change via modal) --}}
                            @php
                                $status = $user->status ?? '-';
                                $badgeClass = match ($status) {
                                    'aktif' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'review' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'reject' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200',
                                };
                            @endphp

                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-medium {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </div>

                        <div class="my-6 h-px w-full bg-gray-200 dark:bg-gray-800"></div>

                        {{-- Quick info --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email ?? '-' }}</p>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Phone</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone ?? '-' }}</p>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Sosmed</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->sosmed ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
