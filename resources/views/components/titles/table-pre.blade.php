<x-tables.header>
    <x-tables.header.container>
        <x-page.heading>Titles</x-page.heading>
        <x-tables.header.metadata>
            <x-tables.header.metadata-label>All Titles</x-tables.header.metadata-label>
            <x-tables.header.metadata-count class="me-2">
                {{ $this->builder()->count() }}
            </x-tables.header.metadata-count>
            <x-tables.header.metadata-statuses enum="\App\Enums\TitleStatus"/>
        </x-tables.header.metadata>
    </x-tables.header.container>

    <x-tables.actions-container>
        <x-buttons.add-new component="titles.modals.form-modal">Add Title</x-buttons.add-new>
    </x-tables.actions-container>
</x-tables.header>
