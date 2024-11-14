@props(['inGrid' => false])

<div {{ $attributes->class(['flex flex-col shadow-card background-card']) }}>
    {{ $slot }}
</div>
