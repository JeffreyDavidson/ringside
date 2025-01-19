<x-form-modal>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form-modal.modal-input>
            <x-form.inputs.text label="{{ __('managers.first_name') }}" name="modelForm.first_name" placeholder="John"
                wire:model="modelForm.first_name" />
        </x-form-modal.modal-input>
        <x-form-modal.modal-input>
            <x-form.inputs.text label="{{ __('managers.last_name') }}" name="modelForm.last_name" placeholder="Smith"
                wire:model="modelForm.last_name" />
        </x-form-modal.modal-input>
    </div>

    <x-form-modal.modal-input>
        <x-form.inputs.date label="{{ __('employments.started_at') }}" name="modelForm.start_date"
            wire:model="modelForm.start_date" />
    </x-form-modal.modal-input>
</x-form-modal>
