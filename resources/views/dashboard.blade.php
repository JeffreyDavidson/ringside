<x-layouts.app>
    <x-container-fixed>
        <x-page.header>
            <x.page.header-container>
                <x-page.heading>Dashboard</x-page.heading>
                <x-page.description>Center Hub</x-page.description>
            </x.page.header-container>
        </x-page.header>
    </x-container-fixed>

    <x-container-fixed>
        Dashboard Content Here
        <x-newmenu.mainmenu :buttonLabel="'Menu'" :index=0>
            <x-testmenu.item>Item 1</x-testmenu.item>
            <x-testmenu.submenu :buttonLabel="'Item 2'" :index=1>
                <x-testmenu.item>Item A</x-testmenu.item>
                <x-testmenu.item>Item B</x-testmenu.item>
            </x-testmenu.submenu>
            <x-newmenu.item>Item 3</x-newmenu.item>
            <x-newmenu.submenu :buttonLabel="'Item 4'" :index=2>
                <x-newmenu.item>Item Q</x-newmenu.item>
                <x-newmenu.submenu :buttonLabel="'Item R'" :index=5>
                    <x-newmenu.item>Item T</x-newmenu.item>
                    <x-newmenu.submenu :buttonLabel="'Item U'" :index=8>
                        <x-newmenu.item>Item Z</x-newmenu.item>
                    </x-newmenu.submenu>
                </x-newmenu.submenu>
            </x-newmenu.submenu>
            <x-newmenu.submenu :buttonLabel="'Item 5'" :index=3>
                <x-newmenu.item>Item QBB</x-newmenu.item>
                <x-newmenu.item>Item RBB</x-newmenu.item>
            </x-newmenu.submenu>
        </x-newmenu.mainmenu>
    </x-container-fixed>
</x-layouts.app>
