@props([
    'isActive' => false,
])

<x-menu.menu-link @class([
    'text-nowrap text-sm',
    'text-gray-900 font-semibold' => $isActive,
    'text-gray-700 font-medium' => ! $isActive,
])>
    <x-menu.menu-title class="text-nowrap">{{ $slot }}</x-menu.menu-title>
</x-menu.menu-link>
