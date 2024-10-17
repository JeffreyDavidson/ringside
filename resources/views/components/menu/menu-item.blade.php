@props(['icon' => 'ki-element-11'])

<div x-data="{
    open: false,
    toggle() {
        this.open = this.open ? this.close() : true
    },
    close() {
        this.open = false
    }
}" class="flex flex-col">
    <x-menu.menu-link x-click="toggle()" title="{{ $slot }}" :hasSubMenu="isset($subMenu)" :icon="$icon">
        {{ $slot }}
    </x-menu.menu-link>

    @if (isset($subMenu))
        <x-menu.menu-submenu x-show="open">
            {{ $subMenu }}
        </x-menu.menu-submenu>
    @endif
</div>
