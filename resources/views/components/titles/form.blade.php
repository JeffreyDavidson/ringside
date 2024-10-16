<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <x-form.field-groups.text label="{{ __('titles.name') }}" isLive="true" name="form.name" />
        <x-form.field-groups.date label="{{ __('activations.start_date') }}" name="form.activation_date" />
    </div>
</x-form>
