@props(['name' => $attributes->whereStartsWith('wire:model')->first(), 'label'])

<x-form.wrapper :$name :$label>
    <input name="{{ $name }}" type="text"
        {{ $attributes->merge(['class' => 'form-control', 'placeholder' => 'Enter ' . ($label ?? 'Value')]) }} />
</x-form.wrapper>
