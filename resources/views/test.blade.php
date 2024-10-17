<x-layouts.app>
    <div x-data="{ open: false }" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90" class="py-12"  @click.outside="open = false">
        <button x-ref="mainButton" x-on:click="open = !open">Open</button>
        <div x-show="open" x-anchor.bottom-start="$refs.mainButton">
            <ul>
                <x-testmenu.main-item>Item 1</x-testmenu.main-item>
                <x-testmenu.submenu :buttonLabel="'Item 2'" :index=0>
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

            </ul>
        </div>
    </div>
</x-layouts.app>
