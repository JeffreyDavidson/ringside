<x-modal>
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-1">
            <div class="flex flex-col gap-1">
                <x-form.inputs.text label="{{ __('referees.first_name') }}" name="modelForm.first_name" placeholder="John"
                    wire:model="modelForm.first_name" />
            </div>
            <div class="flex flex-col gap-1">
                <x-form.inputs.text label="{{ __('referees.last_name') }}" name="modelForm.last_name" placeholder="Smith"
                    wire:model="modelForm.last_name" />
            </div>
        </div>

        <div class="flex flex-col gap-1">
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
