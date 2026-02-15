@php
    use App\Helpers\MenuHelper;
    $menuGroups = MenuHelper::getMenuGroups();

    // Get current path
    $currentPath = request()->path();
@endphp

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200"
    x-data="{
        openSubmenus: {},
    
        init() {
            this.initializeActiveMenus();
        },
    
        normalize(path) {
            // path dari route() biasanya '/users' atau 'users'
            if (!path) return '';
            return path.replace(/^\/+/, ''); // buang leading slash
        },
    
        isPathActive(menuPath) {
            const cur = this.normalize('{{ $currentPath }}'); // contoh: 'users/5'
            const target = this.normalize(menuPath); // contoh: 'users'
    
            if (!target) return false;
    
            // exact match OR prefix match (users/xxx)
            return cur === target || cur.startsWith(target + '/');
        },
    
        initializeActiveMenus() {
            @foreach ($menuGroups as $groupIndex => $menuGroup)
            @foreach ($menuGroup['items'] as $itemIndex => $item)
                @if (isset($item['subItems']))
                    @foreach ($item['subItems'] as $subItem)
                        if (this.isPathActive('{{ $subItem['path'] }}')) {
                            this.openSubmenus['{{ $groupIndex }}-{{ $itemIndex }}'] = true;
                        } @endforeach
            @endif
            @endforeach
            @endforeach
        },
    
        toggleSubmenu(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            const newState = !this.openSubmenus[key];
    
            if (newState) {
                this.openSubmenus = {};
            }
            this.openSubmenus[key] = newState;
        },
    
        isSubmenuOpen(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            return this.openSubmenus[key] || false;
        },
    
        isActive(path) {
            return this.isPathActive(path);
        }
    }"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">
    <!-- Logo Section -->
    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
        'xl:justify-center' :
        'justify-start'">
        <a href="/">
            <div class="text-gray-900 dark:text-white mb-2 flex items-center justify-center gap-2 text-2xl font-bold">
                <svg class="h-10 w-auto" viewBox="0 0 190 279" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M24.704 64.384L27.52 77.184L29.824 78.848V128H6.528V64.384H24.704ZM55.68 62.592L53.632 83.968H47.488C44.928 83.968 42.112 84.224 39.04 84.736C35.968 85.248 32.0853 86.1013 27.392 87.296L25.984 74.624C30.2507 70.6133 34.6027 67.6267 39.04 65.664C43.5627 63.616 47.9147 62.592 52.096 62.592H55.68ZM112.695 62.592C124.983 62.592 131.127 68.5227 131.127 80.384V128H107.831V87.68C107.831 85.632 107.404 84.224 106.551 83.456C105.783 82.688 104.375 82.304 102.327 82.304C100.194 82.304 97.8897 82.6027 95.415 83.2C93.0257 83.7973 89.9963 84.9067 86.327 86.528L85.175 74.496C89.6123 70.5707 94.1777 67.6267 98.871 65.664C103.564 63.616 108.172 62.592 112.695 62.592ZM83.895 64.384L86.711 77.184L88.759 78.848V128H65.463V64.384H83.895Z"
                        fill="currentColor" />
                    <path
                        d="M49.664 160.592C58.368 160.592 64.512 163.152 68.096 168.272C71.68 173.392 73.472 181.797 73.472 193.488C73.472 198.096 73.0027 202.448 72.064 206.544C71.2107 210.555 69.632 214.181 67.328 217.424C65.024 220.581 61.696 223.056 57.344 224.848C53.0773 226.64 47.488 227.536 40.576 227.536C38.272 227.536 35.5413 227.408 32.384 227.152C29.312 226.981 26.112 226.683 22.784 226.256C19.5413 225.829 16.4693 225.317 13.568 224.72C10.6667 224.037 8.27733 223.269 6.4 222.416L24.96 209.488C26.1547 209.915 27.648 210.299 29.44 210.64C31.3173 210.981 33.1947 211.237 35.072 211.408C37.0347 211.493 38.6987 211.536 40.064 211.536C42.4533 211.365 44.3307 210.811 45.696 209.872C47.0613 208.848 48.0427 207.056 48.64 204.496C49.2373 201.936 49.536 198.309 49.536 193.616C49.536 189.52 49.28 186.405 48.768 184.272C48.256 182.053 47.2747 180.517 45.824 179.664C44.4587 178.811 42.5387 178.384 40.064 178.384C37.5893 178.384 35.4133 178.811 33.536 179.664C31.6587 180.432 29.6107 181.456 27.392 182.736L25.728 173.008C27.6907 170.789 29.9093 168.741 32.384 166.864C34.8587 164.987 37.5467 163.493 40.448 162.384C43.4347 161.189 46.5067 160.592 49.664 160.592ZM29.568 136.4L29.824 155.472C29.9093 158.459 29.7387 161.445 29.312 164.432C28.9707 167.333 28.288 170.107 27.264 172.752L29.568 174.416V223.44L6.4 222.416V136.4H29.568ZM134.07 160.592C146.358 160.592 152.502 166.523 152.502 178.384V226H129.206V185.68C129.206 183.632 128.779 182.224 127.926 181.456C127.158 180.688 125.75 180.304 123.702 180.304C121.569 180.304 119.265 180.603 116.79 181.2C114.401 181.797 111.371 182.907 107.702 184.528L105.398 173.776C110.006 169.424 114.742 166.139 119.606 163.92C124.555 161.701 129.377 160.592 134.07 160.592ZM110.134 136.4L110.262 156.752C110.262 160.336 110.006 163.792 109.494 167.12C108.982 170.448 108.427 173.136 107.83 175.184L110.134 176.848V226H86.838V136.4H110.134Z"
                        fill="currentColor" />
                    <path d="M15 50.6005V28H181V226" stroke="currentColor" stroke-width="18" />
                    <path d="M189 250L29 250" stroke="currentColor" stroke-width="17" />
                    <path d="M7 242H29V277L18 266L7 277V242Z" fill="currentColor" stroke="currentColor" />
                </svg>

            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                @foreach ($menuGroups as $groupIndex => $menuGroup)
                    <div>
                        <!-- Menu Group Title -->
                        <h2 class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                            'lg:justify-center' : 'justify-start'">
                            <template
                                x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                <span>{{ $menuGroup['title'] }}</span>
                            </template>
                            <template
                                x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                                        fill="currentColor" />
                                </svg>
                            </template>
                        </h2>

                        <!-- Menu Items -->
                        <ul class="flex flex-col gap-1">
                            @foreach ($menuGroup['items'] as $itemIndex => $item)
                                <li>
                                    @if (isset($item['subItems']))
                                        <!-- Menu Item with Submenu -->
                                        <button @click="toggleSubmenu({{ $groupIndex }}, {{ $itemIndex }})"
                                            class="menu-item group w-full"
                                            :class="[
                                                isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) ?
                                                'menu-item-active' : 'menu-item-inactive',
                                                !$store.sidebar.isExpanded && !$store.sidebar.isHovered ?
                                                'xl:justify-center' : 'xl:justify-start'
                                            ]">

                                            <!-- Icon -->
                                            <span
                                                :class="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) ?
                                                    'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                                {!! MenuHelper::getIconSvg($item['icon']) !!}
                                            </span>

                                            <!-- Text -->
                                            <span
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="menu-item-text flex items-center gap-2">
                                                {{ $item['name'] }}
                                                @if (!empty($item['new']))
                                                    <span class="absolute right-10"
                                                        :class="isActive('{{ $item['path'] ?? '' }}') ?
                                                            'menu-dropdown-badge menu-dropdown-badge-active' :
                                                            'menu-dropdown-badge menu-dropdown-badge-inactive'">
                                                        new
                                                    </span>
                                                @endif
                                            </span>

                                            <!-- Chevron Down Icon -->
                                            <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="ml-auto w-5 h-5 transition-transform duration-200"
                                                :class="{
                                                    'rotate-180 text-brand-500': isSubmenuOpen({{ $groupIndex }},
                                                        {{ $itemIndex }})
                                                }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <!-- Submenu -->
                                        <div
                                            x-show="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)">
                                            <ul class="mt-2 space-y-1 ml-9">
                                                @foreach ($item['subItems'] as $subItem)
                                                    <li>
                                                        <a href="{{ $subItem['path'] }}" class="menu-dropdown-item"
                                                            :class="isActive('{{ $subItem['path'] }}') ?
                                                                'menu-dropdown-item-active' :
                                                                'menu-dropdown-item-inactive'">
                                                            {{ $subItem['name'] }}
                                                            <span class="flex items-center gap-1 ml-auto">
                                                                @if (!empty($subItem['new']))
                                                                    <span
                                                                        :class="isActive('{{ $subItem['path'] }}') ?
                                                                            'menu-dropdown-badge menu-dropdown-badge-active' :
                                                                            'menu-dropdown-badge menu-dropdown-badge-inactive'">
                                                                        new
                                                                    </span>
                                                                @endif
                                                                @if (!empty($subItem['pro']))
                                                                    <span
                                                                        :class="isActive('{{ $subItem['path'] }}') ?
                                                                            'menu-dropdown-badge-pro menu-dropdown-badge-pro-active' :
                                                                            'menu-dropdown-badge-pro menu-dropdown-badge-pro-inactive'">
                                                                        pro
                                                                    </span>
                                                                @endif
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <!-- Simple Menu Item -->
                                        <a href="{{ $item['path'] }}" class="menu-item group"
                                            :class="[
                                                isActive('{{ $item['path'] }}') ? 'menu-item-active' :
                                                'menu-item-inactive',
                                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store
                                                    .sidebar.isMobileOpen) ?
                                                'xl:justify-center' :
                                                'justify-start'
                                            ]">

                                            <!-- Icon -->
                                            <span
                                                :class="isActive('{{ $item['path'] }}') ? 'menu-item-icon-active' :
                                                    'menu-item-icon-inactive'">
                                                {!! MenuHelper::getIconSvg($item['icon']) !!}
                                            </span>

                                            <!-- Text -->
                                            <span
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="menu-item-text flex items-center gap-2">
                                                {{ $item['name'] }}
                                                @if (!empty($item['new']))
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-brand-500 text-white">
                                                        new
                                                    </span>
                                                @endif
                                            </span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>

    </div>
</aside>

<!-- Mobile Overlay -->
<div x-show="$store.sidebar.isMobileOpen" @click="$store.sidebar.setMobileOpen(false)"
    class="fixed z-50 h-screen w-full bg-gray-900/50"></div>
