@props([
    'isActive' => false,
])

<x-mega-menu.menu-item>
    <a {{ $attributes }} @class([
        'menu-link text-nowrap text-sm text-gray-800',
        'text-gray-900 font-medium' => $isActive,
        'hover:text-primary' => !$isActive,
    ])>
        <span class="flex items-center grow leading-none text-nowrap">
            {{ $slot }}
        </span>
    </a>
</x-mega-menu.menu-item>
