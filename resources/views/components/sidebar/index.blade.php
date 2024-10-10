<div
    {{ $attributes->merge([
        'class' =>
            'sidebar dark:bg-coal-600 bg-light border-r border-r-gray-200 dark:border-r-coal-100 fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0',
    ]) }}>
    <x-sidebar.header>
        <a class="dark:hidden" href="{{ route('dashboard') }}">
            <img class="default-logo min-h-[22px] max-w-none" src="{{ Vite::image('app/default-logo.svg') }}" />
            <img class="small-logo min-h-[22px] max-w-none" src="{{ Vite::image('app/mini-logo.svg') }}" />
        </a>
        <a class="hidden dark:block" href="{{ route('dashboard') }}">
            <img class="default-logo min-h-[22px] max-w-none" src="{{ Vite::image('app/default-logo-dark.svg') }}" />
            <img class="small-logo min-h-[22px] max-w-none" src="{{ Vite::image('app/mini-logo.svg') }}" />
        </a>
        <button
            class="btn btn-icon btn-icon-md size-[30px] rounded-lg border border-gray-200 dark:border-gray-300 bg-light text-gray-500 hover:text-gray-700 toggle absolute left-full top-2/4 -translate-x-2/4 -translate-y-2/4">
            <i class="ki-filled ki-black-left-line toggle-active:rotate-180 transition-all duration-300"></i>
        </button>
    </x-sidebar.header>
    <x-sidebar.content>
        <!-- Sidebar Menu -->
        <x-menu>
            <x-menu.menu-link icon="ki-home" :href="route('dashboard')">Dashboard</x-menu.menu-link>
            <x-menu.menu-heading>Admin</x-menu.menu-heading>
            <x-menu.menu-item icon="ki-people">
                Roster
                <x-slot:subMenu>
                    <x-menu.menu-link :href="route('wrestlers.index')">Wrestlers</x-menu.menu-link>
                    <x-menu.menu-link :href="route('tag-teams.index')">Tag Teams</x-menu.menu-link>
                    <x-menu.menu-link :href="route('referees.index')">Referees</x-menu.menu-link>
                    <x-menu.menu-link :href="route('managers.index')">Managers</x-menu.menu-link>
                    <x-menu.menu-link :href="route('stables.index')">Stables</x-menu.menu-link>
                </x-slot:subMenu>
            </x-menu.menu-item>
            <x-menu.menu-link icon="ki-cup" :href="route('titles.index')">Titles</x-menu.menu-link>
            <x-menu.menu-link icon="ki-home-3" :href="route('venues.index')">Venues</x-menu.menu-link>
            <x-menu.menu-link icon="ki-calendar" :href="route('events.index')">Events</x-menu.menu-link>
        </x-menu>
        <!-- End of Sidebar Menu -->
    </x-sidebar.content>
</div>
