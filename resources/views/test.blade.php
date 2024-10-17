<x-layouts.app>
    <x-testmenu.mainmenu :buttonLabel="'Menu'" :index=0>
        <x-testmenu.main-item>Item 1</x-testmenu.main-item>
        <x-testmenu.submenu :buttonLabel="'Item 2'" :index=1>
            <x-testmenu.item>Item A</x-testmenu.item>
            <x-testmenu.item>Item B</x-testmenu.item>
        </x-testmenu.submenu>
        <x-testmenu.main-item class="pr-2">Item 3</x-testmenu.main-item>
        <x-testmenu.submenu :buttonLabel="'Item 4'" :index=2>
            <x-testmenu.item>Item Q</x-testmenu.item>
            <x-testmenu.item>Item R</x-testmenu.item>
        </x-testmenu.submenu>
        <x-testmenu.submenu :buttonLabel="'Item 5'" :index=3>
            <x-testmenu.item>Item QBB</x-testmenu.item>
            <x-testmenu.item>Item RBB</x-testmenu.item>
        </x-testmenu.submenu>
    </x-testmenu.mainmenu>
</x-layouts.app>
