<div id="sidebar-content" class="flex grow shrink-0 py-5 pe-2" :class="!sidebarIsOpen ? 'lg:overflow-hidden' : ''" x-on:mouseover="sidebarIsOpen = true" x-on:mouseout="sidebarIsOpen = false">
    <div class="relative scrollbar-thin scrollbar-transparent overflow-y-scroll grow shrink-0 flex ps-2 lg:ps-5 pe-1 lg:pe-3">
        <!-- Sidebar Menu -->
        <x-sidebar.menu class="flex flex-col grow gap-0.5">
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-element-11" />
                    <x-sidebar.menu-link
                        href="{{ route('dashboard') }}"
                        :isCurrent="request()->routeIs('dashboard')">
                        Dashboard
                    </x-sidebar.menu-link>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
            <x-sidebar.menu-heading>User</x-sidebar.menu-heading>
            <div x-data="{
                open: @json(request()->is('roster/*')),
                toggle() {
                    this.open = !this.open
                }
            }">
                <x-sidebar.menu-label @click="toggle">
                    <x-sidebar.menu-icon icon="ki-people" />
                    <x-sidebar.menu-title>Roster</x-sidebar.menu-title>
                    <x-sidebar.menu-accordian-icons/>
                </x-sidebar.menu-label>
                <x-sidebar.menu-accordian x-show="open">
                    <x-sidebar.accordian-link href="{{ route('wrestlers.index') }}"
                        :isCurrent="request()->routeIs('wrestlers.*')">Wrestlers</x-sidebar.accordian-link>
                    <x-sidebar.accordian-link href="{{ route('tag-teams.index') }}" :isCurrent="request()->routeIs('tag-teams.*')">
                        Tag Teams</x-sidebar.accordian-link>
                    <x-sidebar.accordian-link href="{{ route('managers.index') }}"
                        :isCurrent="request()->routeIs('managers.*')">Managers</x-sidebar.accordian-link>
                    <x-sidebar.accordian-link href="{{ route('referees.index') }}"
                        :isCurrent="request()->routeIs('referees.*')">Referees</x-sidebar.accordian-link>
                    <x-sidebar.accordian-link href="{{ route('stables.index') }}"
                        :isCurrent="request()->routeIs('stables.*')">Stables</x-sidebar.accordian-link>
                </x-sidebar.menu-accordian>
            </div>
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-cup" />
                    <x-sidebar.menu-link
                        :href="route('titles.index')"
                        :isCurrent="request()->routeIs('titles.*')">
                        Titles
                    </x-sidebar.menu-link>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-home-3" />
                    <x-sidebar.menu-link
                        :href="route('venues.index')"
                        :isCurrent="request()->routeIs('venues.*')">
                        Venues
                    </x-sidebar.menu-link>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
            <x-sidebar.menu-item>
                <x-sidebar.menu-label>
                    <x-sidebar.menu-icon icon="ki-calendar" />
                    <x-sidebar.menu-link
                        :href="route('events.index')"
                        :isCurrent="request()->routeIs('events.*')">
                        Events
                    </x-sidebar.menu-link>
                </x-sidebar.menu-label>
            </x-sidebar.menu-item>
        </x-sidebar.menu>
        <!-- End of Sidebar Menu -->
    </div>
</div>
