@props([
    'inGrid' => false,
])

<div {{ $attributes->class(['grow ps-7.5 pe-7.5', 'py-5' => !$inGrid, 'p-0' => $inGrid]) }}>
    {{ $slot }}
</div>
