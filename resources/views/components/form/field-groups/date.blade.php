@props([
    'label' => '',
    'isLive' => false,
    'name' => '',
])

<div class="flex flex-col gap-1">
    <x-form.input-label label="{{ $label }}" />
    <x-form.inputs.date wire:model.live="{{ $name }}" />
    <x-form.validation-error field="{{ $name }}" />
</div>
