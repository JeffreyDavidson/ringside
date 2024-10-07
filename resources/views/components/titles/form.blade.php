<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('titles.name') }}"/>
            <x-form.inputs.text wire:model.live="form.name"/>
            <x-form.validation-error field="form.name"/>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('activations.start_date') }}"/>
            <x-form.inputs.date wire:model.live="form.start_date"/>
            <x-form.validation-error field="form.start_date"/>
        </div>
    </div>
</x-form>
