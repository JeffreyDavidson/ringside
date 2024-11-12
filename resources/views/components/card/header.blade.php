@props([
    'asGrid' => false,
])

<div
    {{ $attributes->class([
        'flex items-center justify-between min-h-14 border border-solid border-gray-200 py-3 border-0',
        'ps-5 pe-5' => $asGrid,
        'ps-7.5 pe-7.5' => !$asGrid,
    ]) }}>
    {{ $slot }}
</div>
