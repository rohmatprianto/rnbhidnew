@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Event Photo" />

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

        <form method="POST" action="{{ route('event.eventphoto.update', $event) }}" enctype="multipart/form-data"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs
                   dark:border-gray-800 dark:bg-white/[0.03]">
            @csrf
            @method('PUT')

            <div class="p-5 space-y-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input name="title" value="{{ old('title', $event->title) }}" required maxlength="120"
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
                        <input name="location" value="{{ old('location', $event->location) }}" maxlength="120"
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Event Date
                        </label>
                        <input type="text" name="event_date"
                            value="{{ old('event_date', optional($event->event_date)->format('Y-m-d')) }}" data-datepicker
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        @php($status = old('status', $event->status ?? 'open'))
                        <select name="status" required
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-3 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">
                            <option value="open" @selected($status === 'open')>open</option>
                            <option value="closed" @selected($status === 'closed')>closed</option>
                            <option value="draft" @selected($status === 'draft')>draft</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Current Cover
                        </label>
                        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                            <div class="h-40 bg-gray-100 dark:bg-gray-800">
                                @if ($event->cover_path)
                                    <img src="{{ asset($event->cover_path) }}" alt="{{ $event->title }}"
                                        class="h-40 w-full object-cover">
                                @endif
                            </div>
                        </div>

                        <label class="mt-3 mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Replace Cover (optional)
                        </label>
                        <input type="file" name="cover" accept="image/*"
                            class="block w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jika upload baru, cover lama akan terhapus.
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Price Notes
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Gunakan tanda <span class="font-semibold">;</span> untuk memisahkan opsi.
                        </p>
                        <textarea name="price_notes" rows="4"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                   dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">{{ old('price_notes', $event->price_notes) }}</textarea>
                    </div>
                    {{-- Category Notes --}}
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category / Class List
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Gunakan tanda <span class="font-semibold">;</span> untuk memisahkan opsi.
                        </p>
                        <textarea name="category_notes" rows="3"
                            placeholder="Pisahkan item dengan titik koma. Contoh: Boys 2019; Girls 2019; FFA; Open Mini"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700
               focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none
               dark:border-gray-800 dark:bg-white/[0.03] dark:text-gray-200">{{ old('category_notes', $event->category_notes) }}</textarea>

                        @error('category_notes')
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
                    Back
                </a>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
