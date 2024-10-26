<x-menu x-data="{ open: false }" @click.outside="open = false">
    <x-testmenu.mainmenu :index=0>
        @foreach ($menuItems as $menuItem)
            {{-- @dd($menuItem) --}}
            @if (!array_key_exists('children', $menuItem))
                <x-testmenu.item icon="{{ $menuItem['icon'] ?? '' }}"
                    href="{{ $menuItem['href'] ?? '' }}">{{ $menuItem['name'] }}</x-testmenu.item>
            @else
                <x-testmenu.submenu buttonLabel="{{ $menuItem['name'] }}" **:index=1**>
                    @foreach ($menuItem['children'] as $item)
                        @if (!array_key_exists('children', $item))
                            <x-testmenu.item href="{{ $item['href'] }}">{{ $item['name'] }}</x-testmenu.item>
                        @else
                            <x-testmenu.submenu buttonLabel="{{ $item['name'] }}" **:index=1**>
                                @foreach ($item['children'] as $childItem)
                                    <x-testmenu.item icon="{{ $childItem['icon'] ?? '' }}"
                                        href="{{ $childItem['href'] }}">{{ $childItem['name'] }}</x-testmenu.item>
                                @endforeach
                            </x-testmenu.submenu>
                        @endif
                    @endforeach
                </x-testmenu.submenu>
            @endif
        @endforeach
    </x-testmenu.mainmenu>
    <x-testmenu.mainmenu :buttonLabel="'Menu'" :index=0>
        <x-testmenu.item>Item 1</x-testmenu.item>
        <x-testmenu.submenu :buttonLabel="'Item 2'" :index=1>
            <x-testmenu.item>Item A</x-testmenu.item>
            <x-testmenu.item>Item B</x-testmenu.item>
        </x-testmenu.submenu>
        <x-testmenu.item>Item 3</x-testmenu.item>
        <x-testmenu.submenu :buttonLabel="'Item 4'" :index=2>
            <x-testmenu.item>Item Q</x-testmenu.item>
            <x-testmenu.submenu :buttonLabel="'Item R'" :index=5>
                <x-testmenu.item>Item T</x-testmenu.item>
                <x-testmenu.submenu :buttonLabel="'Item U'" :index=8>
                    <x-testmenu.item>Item Z</x-testmenu.item>
                </x-testmenu.submenu>
            </x-testmenu.submenu>
        </x-testmenu.submenu>
        <x-testmenu.submenu :buttonLabel="'Item 5'" :index=3>
            <x-testmenu.item>Item QBB</x-testmenu.item>
            <x-testmenu.item>Item RBB</x-testmenu.item>
        </x-testmenu.submenu>
    </x-testmenu.mainmenu>
    {{-- <x-menu.menu-link icon="ki-home" :href="route('dashboard')">Dashboard</x-menu.menu-link>
    <x-menu.menu-heading>Admin</x-menu.menu-heading>
    <x-menu.menu-item icon="ki-people" :subMenuOpen="request()->is('roster/*')">
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
    <x-menu.menu-link icon="ki-calendar" :href="route('events.index')">Events</x-menu.menu-link> --}}
</x-menu>
