<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <x-form.field-groups.text label="{{ __('wrestlers.name') }}" isLive="true" name="form.name" />
        <div class="flex flex-col gap-1">
            <div class="columns-3">
                <x-form.field-groups.number label="{{ __('wrestlers.feet') }}" name="form.height_feet" />
                <x-form.field-groups.number label="{{ __('wrestlers.inches') }}" name="form.height_inches" />
                <x-form.field-groups.number label="{{ __('wrestlers.weight') }}" name="form.weight" />
            </div>
        </div>
        <x-form.field-groups.text label="{{ __('wrestlers.hometown') }}" name="form.hometown" />
        <x-form.field-groups.text label="{{ __('wrestlers.signature_move') }}" name="form.signature_move" />
        <x-form.field-groups.date label="{{ __('employments.start_date') }}" name="form.start_date" />
    </div>
</x-form>
