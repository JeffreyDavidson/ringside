@props([
    'withIcon' => false
])
<button
    {{ $attributes->merge([
        'class' => 'inline-flex items-center cursor-pointer leading-none h-10 rounded-md gap-1.5 ps-px pe-px border border-solid border-transparent font-medium text-2sm' . $withIcon ? 'justify-center shrink-0 p-0 gap-0 w-10' : '',
    ]) }}>
    {{ $slot }}
</button>
