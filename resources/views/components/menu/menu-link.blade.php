<a class="flex items-center border border-transparent grow active:bg-secondary-active active:rounded-lg hover:bg-secondary-active hover:rounded-lg gap-[14px] pl-[10px] pr-[10px] py-[8px] m-0"
    {{ $attributes }}>
    @isset($icon)
        <x-menu.menu-icon :$icon/>
    @else
        <x-menu.menu-bullet/>
    @endisset
    <x-menu.menu-title>{{ $slot }}</x-menu.menu-title>
</a>
