<x-modal>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-1">
            <x-form.inputs.text label="{{ __('stables.name') }}" name="modelForm.name" placeholder="Testing Name Here"
                wire:model="modelForm.name" />
        </div>

        <div class="flex flex-col gap-1">
            <x-form.inputs.date label="{{ __('activations.started_at') }}" name="modelForm.start_date"
                wire:model="modelForm.start_date" />
        </div>
    </div>

    <x-slot:footer>
        <div class="flex gap-4">
            <x-buttons.light wire:click="clear">Clear</x-buttons.light>
            <x-buttons.primary wire:click="save">Save</x-buttons.primary>
        </div>
    </x-slot:footer>
</x-modal>
