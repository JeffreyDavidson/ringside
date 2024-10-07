<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('events.name') }}"/>
            <x-form.inputs.text wire:model.live="form.name"/>
            <x-form.validation-error field="form.name"/>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('events.date') }}"/>
            <x-form.inputs.date wire:model.live="form.date"/>
            <x-form.validation-error field="form.date"/>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('events.venue') }}"/>
            <x-form.inputs.select
                :options="$this->form->showOptions()"
                wire:model.live="form.venue_id"
                :select_name="__('core.venue')"
                select_type="a"/>
            <x-form.validation-error field="form.venue_id"/>
        </div>
        <div class="flex flex-col gap-1">
            <x-form.input-label label="{{ __('events.preview') }}"/>
            <x-form.inputs.textarea wire:model.live="form.preview"/>
            <x-form.validation-error field="form.preview"/>
        </div>
    </div>
</x-form>
