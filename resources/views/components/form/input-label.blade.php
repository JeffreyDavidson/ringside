@props([
    'label' => '',
])

<label
    {{ $attributes->merge(['class' => 'flex w-full font-normal text-gray-900 text-2sm']) }}>
    {{ $label }}:
</label>
