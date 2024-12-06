<div class="sidebar-content flex grow shrink-0 py-5 pr-2" id="sidebar_content">
    <div class="scrollable-y-hover grow shrink-0 flex pl-2 lg:pl-5 pr-1 lg:pr-3">
        <!-- Sidebar Menu -->
        <x-menu class="flex flex-col grow gap-0.5">
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-home" />
                    <x-sidebar.menu-title :href="route('dashboard')">Dashboard</x-sidebar.menu-title>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
            <x-sidebar.menu-heading>User</x-sidebar.menu-heading>
            <div class="flex flex-col p-0 m-0" x-data="{
                open: false,
                toggle() {
                    this.open = ! this.open
                }
            }">
                <div class="p-0 m-0 flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]">
                    <span class="flex shrink-0 items-start text-gray-500 dark:text-gray-400 w-[20px]">
                        <i class="ki-filled ki-profile-circle ..."></i>
                    </span>
                    <span class="flex items-center grow text-sm font-medium text-gray-800 menu-item-active:text-primary menu-link-hover:!text-primary">
                        User Management
                    </span>
                    <span @click="toggle" class="flex items-center text-gray-400 w-[20px] shrink-0 justify-end ms-1 me-[-10px]">
                        <i x-show="!open" class="ki-filled ki-plus text-2xs"></i>
                        <i x-show="open" class="ki-filled ki-minus text-2xs"></i>
                    </span>
                </div>
                <div x-show="open" class="p-0 m-0 gap-0.5 ps-[10px] relative before:absolute before:start-[20px] before:top-0 before:bottom-0 before:border-s before:border-gray-200">
                    <div class="flex flex-col m-0 p-0">
                        <a class="flex m-0 p-0 border border-transparent items-center grow menu-item-active:bg-secondary-active dark:menu-item-active:bg-coal-300 dark:menu-item-active:border-gray-100 menu-item-active:rounded-lg hover:bg-secondary-active dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px] group" href="{{ route('users.index') }}">
                            <span class="items-center shrink-0 flex w-[6px] -start-[3px] relative before:absolute before:top-0 before:size-[6px] before:rounded-full before:-translate-y-1/2 "></span>
                            <span class="flex items-center grow text-2sm font-normal text-gray-800 group-hover:text-primary">Users</span>
                        </a>
                    </div>
                    <div class="flex flex-col m-0 p-0">
                        <a class="flex m-0 p-0 border border-transparent items-center grow menu-item-active:bg-secondary-active dark:menu-item-active:bg-coal-300 dark:menu-item-active:border-gray-100 menu-item-active:rounded-lg hover:bg-secondary-active dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px] group" href="{{ route('users.index') }}">
                            <span class="items-center shrink-0 flex w-[6px] -start-[3px] relative before:absolute before:top-0 before:size-[6px] before:rounded-full before:-translate-y-1/2 "></span>
                            <span class="flex items-center grow text-2sm font-normal text-gray-800 group-hover:text-primary">Users</span>
                        </a>
                    </div>
                </div>
            </div>
            <x-sidebar.menu-item
                x-data="{
                    open: true,
                    toggle() {
                        this.open = ! this.open
                    }}">
                <x-sidebar.menu-label hasSub ::isOpen=open>
                    <x-sidebar.menu-icon icon="ki-people" />
                    <x-sidebar.menu-title :href="route('dashboard')">Roster</x-sidebar.menu-title>
                </x-sidebar.menu-label>
                <x-slot:subMenu isOpen>
                    <x-sidebar.menu-link :href="route('wrestlers.index')">Wrestlers</x-sidebar.menu-link>
                    <x-sidebar.menu-link :href="route('tag-teams.index')">Tag Teams</x-sidebar.menu-link>
                    <x-sidebar.menu-link :href="route('referees.index')">Referees</x-sidebar.menu-link>
                    <x-sidebar.menu-link :href="route('managers.index')">Managers</x-sidebar.menu-link>
                    <x-sidebar.menu-link :href="route('stables.index')">Stables</x-sidebar.menu-link>
                </x-slot:subMenu>
            </x-sidebar.menu-item>
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-cup" />
                    <x-sidebar.menu-title :href="route('titles.index')">Titles</x-sidebar.menu-title>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-home-3" />
                    <x-sidebar.menu-title :href="route('venues.index')">Venues</x-sidebar.menu-title>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-calendar" />
                    <x-sidebar.menu-title :href="route('events.index')">Events</x-sidebar.menu-title>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
        </x-menu>
        <!-- End of Sidebar Menu -->
    </div>
</div>
