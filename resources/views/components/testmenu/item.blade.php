@props([
    'icon' => '',
])

<li {{ $attributes->merge()->class(['whitespace-nowrap px-2 flex items-center gap-[10px]']) }}>
    @if ($attributes->has('href'))
        <x-menu.menu-icon :$icon />
        <a class="text-sm font-medium text-gray-800 active:text-primary hover:!text-primary""
            href="{{ $attributes['href'] }}">{{ $slot }}</a>
    @endif
</li>
