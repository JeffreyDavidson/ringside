<x-actions-dropdown>
    @can('update', $event)
        <x-buttons.edit :route="route('events.edit', $event)" />
    @endcan

    @can('delete', $event)
        <x-buttons.delete :route="route('events.destroy', $event)" />
    @endcan
</x-actions-dropdown>
