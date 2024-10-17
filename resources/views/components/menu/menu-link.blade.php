@props([
    'icon' => '',
    'hasSubMenu' => false,
])

<div class="flex flex-col p-0 m-o">
    <a {{ $attributes }} tabindex="0" @class([
        'menu-link border border-transparent items-center grow menu-item-active:bg-secondary-active dark:menu-item-active:bg-coal-300 dark:menu-item-active:border-gray-100 menu-item-active:rounded-lg hover:bg-secondary-active dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg gap-[14px] pl-[10px] pr-[10px] py-[8px]',
        'dark:bg-coal-300 dark:border-gray-100 rounded-lg' =>
            request()->url() === $attributes['href'],
    ])>
        @isset($icon)
            <x-menu.menu-icon :icon="$icon" />
        @else
            <x-menu.menu-bullet />
        @endisset

        <x-menu.menu-title :class="request()->url() === $attributes['href'] ? 'text-primary font-semibold' : ''">
            {{ $slot }}
        </x-menu.menu-title>

        @if ($hasSubMenu)
            <x-menu.menu-dropdown-icons />
        @endif
    </a>
</div>
