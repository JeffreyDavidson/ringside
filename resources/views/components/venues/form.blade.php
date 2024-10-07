<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('venues.name') }}"/>
            <x-form.inputs.text wire:model.live="form.name"/>
            <x-form.validation-error field="form.name"/>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('venues.street_address') }}"/>
            <x-form.inputs.text wire:model.live="form.street_address"/>
            <x-form.validation-error field="form.street_address"/>
        </div>
        <div class="flex flex-col gap-1">
            <div class="columns-3">
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('venues.city') }}"/>
                    <x-form.inputs.text wire:model.live="form.city"/>
                    <x-form.validation-error field="form.city"/>
                </div>
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('venues.state') }}"/>
                    <x-form.inputs.text wire:model.live="form.state"/>
                    <x-form.validation-error field="form.state"/>
                </div>
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('venues.zipcode') }}"/>
                    <x-form.inputs.text wire:model.live="form.zipcode"/>
                    <x-form.validation-error field="form.zipcode"/>
                </div>
            </div>
        </div>
    </div>
</x-form>
