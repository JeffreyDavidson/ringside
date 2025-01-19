<x-form-modal>
    <div class="flex flex-col">
        <x-form.inputs.text label="{{ __('titles.name') }}" name="modelForm.name" placeholder="Testing Name Here"
            wire:model="modelForm.name" />
    </div>

    <div class="flex flex-col">
        <x-form.inputs.date label="{{ __('activations.started_at') }}" name="modelForm.start_date"
            wire:model="modelForm.start_date" />
    </div>
</x-form-modal>
