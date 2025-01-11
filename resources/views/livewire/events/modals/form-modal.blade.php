<x-modal>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2.5">
            <x-form.inputs.text label="{{ __('evenets.name') }}" name="modelForm.name" placeholder="Testing Name Here"
                wire:model="modelForm.name" />
        </div>

        <div class="flex flex-col gap-2.5">
            <x-form.inputs.date label="{{ __('events.date') }}" name="modelForm.date" wire:model="modelForm.date" />
        </div>

        <div class="flex flex-col gap-2.5">x
            <x-form.inputs.select options="modelForm.venues" label="{{ __('events.venue') }}" name="modelForm.venue"
                wire:model="modelForm.venue" />
        </div>

        <div class="flex flex-col gap-2.5">
            <x-form.inputs.textarea label="{{ __('events.preview') }}" name="modelForm.preview"
                placeholder="Example event preview" wire:model="modelForm.preview" />
        </div>
    </div>

    <x-slot:footer>
        <div class="flex gap-4">
            <x-buttons.light wire:click="clear">Clear</x-buttons.light>
            <x-buttons.primary wire:click="save">Save</x-buttons.primary>
        </div>
    </x-slot:footer>
</x-modal>
