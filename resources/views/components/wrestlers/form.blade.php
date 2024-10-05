<form wire:submit="save" id="wrestlerForm">
    <div class="space-y-4">
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('wrestlers.name') }}"/>
            <x-form.inputs.text wire:model.live="form.name"/>
            <x-form.validation-error field="form.name"/>
        </div>
        <div class="flex flex-col gap-1">
            <div class="columns-3">
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('wrestlers.feet') }}"/>
                    <x-form.inputs.number wire:model.live="form.height_feet"/>
                    <x-form.validation-error field="form.height_feet"/>
                </div>
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('wrestlers.inches') }}"/>
                    <x-form.inputs.number wire:model.live="form.height_inches"/>
                    <x-form.validation-error field="form.height_inches"/>
                </div>
                <div class="flex flex-col gap-1">
                    <x-form.input-label label="{{ __('wrestlers.weight') }}"/>
                    <x-form.inputs.number wire:model.live="form.weight"/>
                    <x-form.validation-error field="form.weight"/>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('wrestlers.hometown') }}"/>
            <x-form.inputs.text wire:model.live="form.hometown"/>
            <x-form.validation-error field="form.hometown"/>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('employments.start_date') }}"/>
            <x-form.inputs.date wire:model.live="form.start_date"/>
            <x-form.validation-error field="form.start_date"/>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('wrestlers.signature_move') }}"/>
            <x-form.inputs.text wire:model.live="form.signature_move"/>
            <x-form.validation-error field="form.signature_move"/>
        </div>
    </div>
</form>
