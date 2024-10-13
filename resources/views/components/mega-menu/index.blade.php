<div {{ $attributes->merge(['class' => 'flex items-stretch']) }}>
    <x-mega-menu.container>
        <!-- Mega Menu Wrapper -->
        <x-mega-menu.wrapper>
            <x-mega-menu.menu>
                <x-mega-menu.menu-link href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
                    Dashboard
                </x-mega-menu.menu-link>
                <x-mega-menu.menu-item :isActive="request()->routeIs('titles.*')">
                    Roster
                    <x-slot:subMenu>
                        <x-mega-menu.menu-link href="{{ route('wrestlers.index') }}" :isActive="request()->routeIs('titles.*')">
                            Wrestlers
                        </x-mega-menu.menu-link>
                        <x-mega-menu.menu-link href="{{ route('tag-teams.index') }}" :isActive="request()->routeIs('titles.*')">
                            Tag Teams
                        </x-mega-menu.menu-link>
                        <x-mega-menu.menu-link href="{{ route('referees.index') }}" :isActive="request()->routeIs('titles.*')">
                            Referees
                        </x-mega-menu.menu-link>
                        <x-mega-menu.menu-link href="{{ route('managers.index') }}" :isActive="request()->routeIs('titles.*')">
                            Managers
                        </x-mega-menu.menu-link>
                        <x-mega-menu.menu-link href="{{ route('stables.index') }}" :isActive="request()->routeIs('titles.*')">
                            Stables
                        </x-mega-menu.menu-link>
                    </x-slot:subMenu>
                </x-mega-menu.menu-item>
                <x-mega-menu.menu-link href="{{ route('titles.index') }}" :isActive="request()->routeIs('titles.*')">
                    Titles
                </x-mega-menu.menu-link>
                <x-mega-menu.menu-link href="{{ route('venues.index') }}" :isActive="request()->routeIs('venues.*')">
                    Venues
                </x-mega-menu.menu-link>
                <x-mega-menu.menu-link href="{{ route('events.index') }}" :isActive="request()->routeIs('events.*')">
                    Events
                </x-mega-menu.menu-link>
            </x-mega-menu.menu>
        </x-mega-menu.wrapper>
        <!-- End of Mega Menu Wrapper -->
    </x-mega-menu.container>
</div>
