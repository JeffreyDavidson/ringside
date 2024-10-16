@props([
    'label' => '',
    'isLive' => false,
    'name' => '',
    'options' => [],
    'select_type' => 'an',
    'select_name' => 'Option',
])

<div class="flex flex-col gap-1">
    <x-form.input-label label="{{ $label }}" />
    <x-form.inputs.select wire:model.live="{{ $name }}" :$options :$select_type :$select_name />
    <x-form.validation-error field="{{ $name }}" />
</div>
