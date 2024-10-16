<x-form {{ $attributes }} wire:submit="save">
    <div class="space-y-4">
        <x-form.field-groups.text label="{{ __('stables.name') }}" isLive="true" name="form.name" />
        <x-form.field-groups.select label="{{ __('stables.wrestlers') }}" name="form.wrestlers" :options="$wrestlers" />
        <x-form.field-groups.select label="{{ __('stables.tag-teams') }}" name="form.tag-teams" :options="$tagTeams" />
        <x-form.field-groups.select label="{{ __('stables.managers') }}" name="form.managers" :options="$managers" />
        <x-form.field-groups.date label="{{ __('employments.start_date') }}" name="form.start_date" />
    </div>
</x-form>
