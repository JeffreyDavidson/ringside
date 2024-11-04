@props(['icon' => 'ki-element-11'])

<div x-data="{
    open: false,
    toggle() {
        this.open = this.open ? this.close() : true
    },
    close() {
        this.open = false
    }
}" {{ $attributes->merge(['class' => 'flex flex-col m-0 p-0']) }}>
    {{ $slot }}
    @if (isset($subMenu))
        <x-menu.menu-accordian class="open ? 'show' : 'hidden'" x-show="open">
            {{ $subMenu }}
        </x-menu.menu-accordian>
    @endif
</div>
