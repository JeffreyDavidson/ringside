<x-form-modal>
    <x-form-modal.modal-input>
        <x-form.inputs.text label="{{ __('wrestlers.name') }}" name="modelForm.name" placeholder="Testing Name Here"
            wire:model="modelForm.name" />
    </x-form-modal.modal-input>

    <x-form-modal.modal-input>
        <x-form.inputs.text label="{{ __('wrestlers.hometown') }}" name="modelForm.hometown" placeholder="Orlando, FL"
            wire:model="modelForm.hometown" />
    </x-form-modal.modal-input>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-form-modal.modal-input>
            <x-form.inputs.text label="{{ __('wrestlers.feet') }}" name="modelForm.height_feet" placeholder="Feet"
                wire:model="modelForm.height_feet" />
        </x-form-modal.modal-input>
        <x-form-modal.modal-input>
            <x-form.inputs.text label="{{ __('wrestlers.inches') }}" name="modelForm.height_inches" placeholder="Inches"
                wire:model="modelForm.height_inches" />
        </x-form-modal.modal-input>
        <x-form-modal.modal-input>
            <x-form.inputs.text label="{{ __('wrestlers.weight') }}" name="modelForm.weight" placeholder="lbs"
                wire:model="modelForm.weight" />
        </x-form-modal.modal-input>
    </div>

    <x-form-modal.modal-input>
        <x-form.inputs.text label="{{ __('wrestlers.signature_move') }}" name="modelForm.signature_move"
            placeholder="This Amazing Finisher" wire:model="modelForm.signature_move" />
    </x-form-modal.modal-input>

    <x-form-modal.modal-input>
        <x-form.inputs.date label="{{ __('employments.started_at') }}" name="modelForm.start_date"
            wire:model="modelForm.start_date" />
    </x-form-modal.modal-input>
</x-form-modal>
