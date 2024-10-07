<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <div class="flex flex-col gap-1">
            <div class="columns-2">
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('managers.first_name') }}"/>
                    <x-form.inputs.text wire:model.live="form.first_name"/>
                    <x-form.validation-error field="form.first_name"/>
                </div>
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('managers.last_name') }}"/>
                    <x-form.inputs.text wire:model.live="form.last_name"/>
                    <x-form.validation-error field="form.last_name"/>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('employments.start_date') }}"/>
            <x-form.inputs.date wire:model.live="form.start_date"/>
            <x-form.validation-error field="form.start_date"/>
        </div>
    </div>
</x-form>
