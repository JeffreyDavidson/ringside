<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <x-form.field-groups.text label="{{ __('venues.name') }}" isLive="true" name="form.name" />
        <x-form.field-groups.text label="{{ __('venues.street_address') }}" name="form.street_address" />
        <div class="flex flex-col gap-1">
            <div class="columns-3">
                <x-form.field-groups.text label="{{ __('venues.city') }}" name="form.city" />
                <x-form.field-groups.text label="{{ __('venues.state') }}" name="form.state" />
                <x-form.field-groups.text label="{{ __('venues.zipcode') }}" name="form.zipcode" />
            </div>
        </div>
    </div>
</x-form>
