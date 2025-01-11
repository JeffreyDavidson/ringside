<x-modal>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2.5">
            <x-form.inputs.text label="{{ __('tag-teams.name') }}" name="modelForm.name" placeholder="Testing Name Here"
                wire:model="modelForm.name" />
        </div>

        <div class="flex flex-col gap-2.5">
            <x-form.inputs.text label="{{ __('tag-teams.signature_move') }}" name="modelForm.signature_move"
                placeholder="This Amazing Finisher" wire:model="modelForm.signature_move" />
        </div>

        <div class="flex flex-col gap-2.5">
            <x-form.inputs.date label="{{ __('employments.started_at') }}" name="modelForm.start_date"
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
