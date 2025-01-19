<x-form-modal>
    <x-form-modal.modal-input>
        <x-form.inputs.text label="{{ __('tag-teams.name') }}" wire:model="modelForm.name" />
    </x-form-modal.modal-input>

    <x-form-modal.modal-input>
        <x-form.inputs.text label="{{ __('tag-teams.signature_move') }}" wire:model="modelForm.signature_move" />
    </x-form-modal.modal-input>

    <x-form-modal.modal-input>
        <x-form.inputs.date label="{{ __('employments.started_at') }}" wire:model="modelForm.start_date" />
    </x-form-modal.modal-input>
</x-form-modal>
