<div class="sidebar-content flex grow shrink-0 py-5 pr-2" id="sidebar_content">
    <div class="scrollable-y-hover grow shrink-0 flex pl-2 lg:pl-5 pr-1 lg:pr-3">
        <!-- Sidebar Menu -->
        <div class="flex flex-col grow gap-0.5">
            <x-menu.menu-item>
                <x-menu.menu-link icon="ki-home" :href="route('dashboard')">Dashboard</x-menu.menu-link>
            </x-menu.menu-item>
            <x-menu.menu-heading>Admin</x-menu.menu-heading>
            <x-menu.menu-item>
                <x-menu.menu-label icon="ki-people">Roster</x-menu.menu-label>
                <x-slot:subMenu>
                    <x-menu.menu-link :href="route('wrestlers.index')">Wrestlers</x-menu.menu-link>
                    <x-menu.menu-link :href="route('tag-teams.index')">Tag Teams</x-menu.menu-link>
                    <x-menu.menu-link :href="route('referees.index')">Referees</x-menu.menu-link>
                    <x-menu.menu-link :href="route('managers.index')">Managers</x-menu.menu-link>
                    <x-menu.menu-link :href="route('stables.index')">Stables</x-menu.menu-link>
                </x-slot:subMenu>
            </x-menu.menu-item>
            <x-menu.menu-item>
                <x-menu.menu-link icon="ki-cup" :href="route('titles.index')">Titles</x-menu.menu-link>
            </x-menu.menu-item>
            <x-menu.menu-item>
                <x-menu.menu-link icon="ki-home-3" :href="route('venues.index')">Venues</x-menu.menu-link>
            </x-menu.menu-item>
            <x-menu.menu-item>
                <x-menu.menu-link icon="ki-calendar" :href="route('events.index')">Events</x-menu.menu-link>
            </x-menu.menu-item>
        </div>
        <!-- End of Sidebar Menu -->
    </div>
</div>
