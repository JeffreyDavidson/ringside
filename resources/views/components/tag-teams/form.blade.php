<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <x-form.field-groups.text label="{{ __('tag-teams.name') }}" isLive="true" name="form.name" />
        <x-form.field-groups.select label="{{ __('tag-teams.wrestlerA') }}" name="form.wrestlerA" :options="$wrestlers" />
        <x-form.field-groups.select label="{{ __('tag-teams.wrestlerB') }}" name="form.wrestlerB" :options="$wrestlers" />
        <x-form.field-groups.date label="{{ __('tag-teams.signature_move') }}" name="form.signature_move" />
        <x-form.field-groups.date label="{{ __('employments.start_date') }}" name="form.start_date" />
    </div>
</x-form>
