<div {{ $attributes->merge(['class' => 'flex items-center grow border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]']) }}>
    <x-menu.menu-icon :$icon/>
    <x-menu.menu-title>{{ $slot }}</x-menu.menu-title>
    <x-menu.menu-arrow click="toggle()"/>
</div>
