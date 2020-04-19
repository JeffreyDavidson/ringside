<x-layouts.app>
    <x-subheader title="Managers">
        <x-slot name="filters">
            @include('managers.partials.filters')
        </x-slot>
        <x-slot name="search">
            <livewire:managers.search-managers />
        </x-slot>
        <x-slot name="actions">
            <a href="{{ route('managers.create') }}" class="btn btn-label-brand btn-bold">
                Create Managers
            </a>
        </x-slot>
    </x-subheader>
    <x-content>
        <x-portlet title="Employed Managers">
            <div class="kt-portlet__body">
                <livewire:managers.employed-managers>
            </div>
        </x-portlet>

        <x-portlet title="Released Managers">
            <div class="kt-portlet__body">
                <livewire:managers.released-managers>
            </div>
        </x-portlet>

        <x-portlet title="Retired Managers">
            <div class="kt-portlet__body">
                <livewire:managers.retired-managers>
            </div>
        </x-portlet>
    </x-content>
</x-layouts.app>
