@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Change Photo">
        <x-slot:breadcrumbs>
            <li>
                <a href="{{ route('dashboard') }}"
                    class="text-gray-700 hover:text-brand-600 dark:text-gray-400 dark:hover:text-brand-500">Dashboard</a>
            </li>
            <li>
                <span class="text-gray-700 dark:text-gray-400">Change Photo</span>
            </li>
        </x-slot:breadcrumbs>
    </x-common.page-breadcrumb>

    <x-layouts.settings title="Change Photo" description="Update your profile photo">
        @if (session('status'))
            <div class="mb-6">
                <x-ui.alert variant="success" :message="session('status')" />
            </div>
        @endif
        <div class="w-full md:w-1/2">
            {{-- Photo --}}
            <div class="relative">
                <img src="{{ $user->photo_path ? asset($user->photo_path) : asset('images/assets/userphoto/default.png') }}"
                    alt="{{ $user->name }}" class="h-60 w-full object-cover md:object-fill" />
            </div>

            {{-- Photo update form (SEPARATE) --}}
            <div class="border-b border-gray-200 py-4 dark:border-gray-800">
                <form action="{{ route('settings.profile.photo.update') }}" method="POST" enctype="multipart/form-data"
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
        </div>
    </x-layouts.settings>
@endsection
