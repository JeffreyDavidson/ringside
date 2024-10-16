<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <div class="flex flex-col gap-1">
            <div class="columns-2">
                <x-form.field-groups.text label="{{ __('referees.first_name') }}" isLive="true" name="form.first_name" />
                <x-form.field-groups.text label="{{ __('referees.last_name') }}" isLive="true" name="form.last_name" />
            </div>
        </div>
        <x-form.field-groups.date label="{{ __('employments.start_date') }}" name="form.start_date" />
    </div>
</x-form>
