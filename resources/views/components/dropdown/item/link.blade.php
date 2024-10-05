@props([
    'label' => '',
    'icon' => '',
])

<a {{ $attributes->merge(['class' => 'cursor-pointer flex items-center grow mx-2.5 p-2.5 rounded-md hover:bg-coal-300 hover:text-gray-900']) }}">
    <span class="flex items-center shrink-0 mr-2.5">
        <i class="ki-filled {{ $icon }}"></i>
    </span>
    <span class="flex items-center leading-none font-medium text-2sm text-gray-800">{{ $label }}</span>
</a>
