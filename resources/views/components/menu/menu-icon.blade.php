@props([
    'icon' => 'testing',
])

<span {{ $attributes->merge(['class' => 'flex shrink-0']) }}>
    <i class="ki-filled {{ $icon }} text-lg"></i>
</span>
