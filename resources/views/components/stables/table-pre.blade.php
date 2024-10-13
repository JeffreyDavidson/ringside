<x-tables.header>
    <x-tables.header.container>
        <x-page.heading>Stables</x-page.heading>
        <x-tables.header.metadata>
            <x-tables.header.metadata-label>All Stables</x-tables.header.metadata-label>
            <x-tables.header.metadata-count class="me-2">
                {{ $this->builder()->count() }}
            </x-tables.header.metadata-count>
            <x-tables.header.metadata-statuses enum="\App\Enums\StableStatus"/>
        </x-tables.header.metadata>
    </x-tables.header.container>

    <x-tables.actions-container>
        <x-buttons.add-new component="stables.modals.form-modal">Add Stable</x-buttons.add-new>
    </x-tables.actions-container>
</x-tables.header>
