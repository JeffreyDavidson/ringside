<x-layouts.app>
    <x-subheader displayRecordsCount="true" search="true" filters="venues.partials.filters" title="Venues">
        <x-slot name="actions">
            <a href="{{ route('venues.create') }}" class="btn btn-label-brand btn-bold">
                Create Venues
            </a>
        </x-slot>
    </x-subheader>
    <x-content>
        <x-portlet title="Venues">
            <div class="kt-portlet__body">
                <livewire:venues.all-venues>
            </div>
        </x-portlet>
    </x-content>
</x-layouts.app>
