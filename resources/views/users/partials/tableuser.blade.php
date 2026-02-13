<div class="max-w-full overflow-x-auto custom-scrollbar">
    <table class="w-full min-w-[1102px]">
        <thead>
            <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Name</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Email</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Phone</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Sosmed</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                </th>
            </tr>
        </thead>

        <tbody>
            @forelse ($users as $user)
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 overflow-hidden rounded-full">
                                <img src="{{ $user->photo_path ? asset($user->photo_path) : asset('images/assets/userphoto/default.png') }}"
                                    alt="{{ $user->name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                    {{ $user->name }}
                                </span>
                                <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ (bool) ($user->is_admin ?? false) ? 'Admin' : 'User' }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $user->email }}</p>
                    </td>

                    <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $user->phone ?? '-' }}</p>
                    </td>

                    <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $user->sosmed ?? '-' }}</p>
                    </td>

                    <td class="px-5 py-4 sm:px-6">
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
                            <div x-data="{ open: false }" class="inline-flex">
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

                                <div x-show="open" x-cloak
                                    class="fixed inset-0 z-[99999] flex items-center justify-center" aria-modal="true"
                                    role="dialog">
                                    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

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
                                            <form method="POST" action="{{ route('users.status.update', $user) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="aktif">
                                                <button type="submit"
                                                    class="w-full rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">Aktif</button>
                                            </form>

                                            <form method="POST" action="{{ route('users.status.update', $user) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="review">
                                                <button type="submit"
                                                    class="w-full rounded-lg bg-yellow-500 px-3 py-2 text-sm font-medium text-white hover:bg-yellow-600">Review</button>
                                            </form>

                                            <form method="POST" action="{{ route('users.status.update', $user) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="reject">
                                                <button type="submit"
                                                    class="w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">Reject</button>
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
</td>

<td class="px-5 py-4 sm:px-6">
    <div class="flex items-center gap-2">
        @if ($tab !== 'deleteusers')
            <a href="{{ route('users.edit', $user) }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
            </a>

            <a href="{{ route('users.show', $user) }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>

            </a>

            <form action="{{ route('users.destroy', $user) }}" method="POST"
                onsubmit="return confirm('Delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-2 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L5 6m5 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                    </svg>
                </button>
            </form>
        @else
            <form action="{{ route('users.restore', $user->id) }}" method="POST"
                onsubmit="return confirm('Restore this user?');">
                @csrf
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Restore
                </button>
            </form>
        @endif
    </div>
</td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-5 py-8 text-center">
        <p class="text-gray-500 dark:text-gray-400">No users found.</p>
    </td>
</tr>
@endforelse
</tbody>
</table>
</div>

{{-- Meta untuk Alpine pagination --}}
<div id="usersTableRoot" data-current-page="{{ $users->currentPage() }}"
    data-total-pages="{{ $users->lastPage() }}" data-prev-url="{{ $users->previousPageUrl() ?? '' }}"
    data-next-url="{{ $users->nextPageUrl() ?? '' }}"></div>

@if ($users->total() > 0)
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        <div class="flex items-center justify-between">

            {{-- Previous --}}
            <button @click="prevPage" :disabled="currentPage === 1"
                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
                        fill="currentColor" />
                </svg>
                <span class="hidden sm:inline">Previous</span>
            </button>

            {{-- Mobile Page info --}}
            <span class="block text-sm font-medium text-gray-700 dark:text-gray-400 sm:hidden">
                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
            </span>

            {{-- Desktop Numbers --}}
            <ul class="hidden items-center gap-0.5 sm:flex">
                <template x-for="page in displayedPages" :key="page">
                    <li>
                        <button x-show="page !== '...'" @click="goToPage(page)"
                            :class="currentPage === page ? 'bg-blue-500 text-white' :
                                'text-gray-700 hover:bg-blue-500/[0.08] hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-500'"
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-theme-sm font-medium"
                            x-text="page"></button>

                        <span x-show="page === '...'"
                            class="flex h-10 w-10 items-center justify-center text-gray-500">...</span>
                    </li>
                </template>
            </ul>

            {{-- Next --}}
            <button @click="nextPage" :disabled="currentPage === totalPages"
                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''"
                class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:px-3.5">
                <span class="hidden sm:inline">Next</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z"
                        fill="currentColor" />
                </svg>
            </button>

        </div>
    </div>
@endif
