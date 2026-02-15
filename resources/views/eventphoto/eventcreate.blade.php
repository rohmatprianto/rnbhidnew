@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Add Event Photo" />

    <div class="max-w-3xl space-y-4">
        @if ($errors->any())
            <div
                class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700
                       dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-200">
                <div class="font-semibold">Periksa input:</div>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('event.eventphoto.store') }}" enctype="multipart/form-data"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs
                   dark:border-gray-800 dark:bg-white/[0.03]">
            @csrf

            <div class="p-5 space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input name="title" value="{{ old('title') }}" required maxlength="120"
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
                        @error('title')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Location
                        </label>
                        <input name="location" value="{{ old('location') }}" maxlength="120"
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
                        @error('location')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Event Date
                        </label>
                        <input type="text" name="event_date" value="{{ old('event_date') }}" data-datepicker
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
                        @error('event_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                            @php($status = old('status', 'open'))
                            <option value="open" @selected($status === 'open')>open</option>
                            <option value="closed" @selected($status === 'closed')>closed</option>
                            <option value="draft" @selected($status === 'draft')>draft</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cover (optional)
                        </label>
                        <input type="file" name="cover" accept="image/*"
                            class="block w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 2MB. JPG/PNG/WebP.</p>
                        @error('cover')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Price Notes
                        </label>
                        <textarea name="price_notes" rows="4"
                            placeholder="Pisahkan item dengan titik koma. Contoh: 2 Moto / Kualifikasi Rp.50.000; Lebih dari 2 Moto Rp.70.000"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">{{ old('price_notes', '2 Moto / Kualifikasi Rp.50.000; Lebih dari 2 Moto Rp.70.000') }}</textarea>
                        @error('price_notes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div
                class="flex items-center justify-between gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4
                        dark:border-gray-800 dark:bg-white/[0.02]">
                <a href="{{ route('event.eventphoto.index') }}"
                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-white
                           dark:border-gray-700 dark:text-gray-200 dark:hover:bg-white/[0.06]">
                    Cancel
                </a>

                <button type="submit"
                    class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                    Save
                </button>
            </div>
        </form>
    </div>
@endsection
