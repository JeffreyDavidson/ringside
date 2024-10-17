@props([
    'subMenuOpen' => false,
])

<div {{ $attributes }} @class([
    'p-0 pl-[10px] m-0 hidden relative gap-0.5 h-0 before:absolute before:left-[20px] before:top-0 before:bottom-0 before:border-l before:border-gray-200',
    'show' => $subMenuOpen,
])>
    {{ $slot }}
</div>
