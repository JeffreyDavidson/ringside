<x-layouts.app>
    <x-subheader title="Titles">
        <x-slot name="filters">
            @include('titles.partials.filters')
        </x-slot>
        <x-slot name="search">
            <livewire:titles.search-titles />
        </x-slot>
        <x-slot name="actions">
            <a href="{{ route('titles.create') }}" class="btn btn-label-brand btn-bold">
                Create Titles
            </a>
        </x-slot>
    </x-subheader>
    <x-content>
        <x-portlet title="Active Titles">
            <div class="kt-portlet__body">
                <livewire:titles.active-titles>
            </div>
        </x-portlet>

        <x-portlet title="Inactive Titles">
            <div class="kt-portlet__body">
                <livewire:titles.inactive-titles>
            </div>
        </x-portlet>

        <x-portlet title="Retired Titles">
            <div class="kt-portlet__body">
                <livewire:titles.retired-titles>
            </div>
        </x-portlet>
    </x-content>
</x-layouts.app>
