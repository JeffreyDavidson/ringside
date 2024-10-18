<x-layouts.app>
    <x-testmenu.mainmenu :buttonLabel="'Menu'" :index=0>
        <x-testmenu.item >Item 1</x-testmenu.item>
        <x-testmenu.submenu  :buttonLabel="'Item 2'" :index=1>
            <x-testmenu.item >Item A</x-testmenu.item>
            <x-testmenu.item >Item B</x-testmenu.item>
        </x-testmenu.submenu>
        <x-testmenu.item>Item 3</x-testmenu.item>
        <x-testmenu.submenu :buttonLabel="'Item 4'" :index=2>
            <x-testmenu.item >Item Q</x-testmenu.item>
                <x-testmenu.submenu :buttonLabel="'Item R'" :index=5>
                    <x-testmenu.item >Item T</x-testmenu.item>
                    <x-testmenu.submenu :buttonLabel="'Item U'" :index=8>
                        <x-testmenu.item >Item Z</x-testmenu.item>
                    </x-testmenu.submenu>
                </x-testmenu.submenu>
            </x-testmenu.submenu>
        <x-testmenu.submenu :buttonLabel="'Item 5'" :index=3>
            <x-testmenu.item>Item QBB</x-testmenu.item>
            <x-testmenu.item >Item RBB</x-testmenu.item>
        </x-testmenu.submenu>
    </x-testmenu.mainmenu>
</x-layouts.app>
