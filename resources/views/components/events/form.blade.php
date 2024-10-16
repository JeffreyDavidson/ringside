<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <x-form.field-groups.text label="{{ __('events.name') }}" isLive="true" name="form.name" />
        <x-form.field-groups.date label="{{ __('events.date') }}" name="form.date" />
        <x-form.field-groups.select label="{{ __('events.venue') }}" name="form.venue" :options="$this->form->showOptions()" :select_name="__('events.venue')"
            select_type="a" />
        <x-form.field-groups.textarea label="{{ __('events.preview') }}" name="form.preview" />
    </div>
</x-form>
