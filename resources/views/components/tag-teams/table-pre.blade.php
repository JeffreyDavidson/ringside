<x-tables.header>
    <x-tables.header.container>
        <x-page.heading>Tag Teams</x-page.heading>
        <x-tables.header.metadata>
            <x-tables.header.metadata-label>All Tag Teams</x-tables.header.metadata-label>
            <x-tables.header.metadata-count class="me-2">
                {{ $this->builder()->count() }}
            </x-tables.header.metadata-count>
            <x-tables.header.metadata-label>Bookable</x-tables.header.metadata-label>
            <x-tables.header.metadata-count>
                {{ $this->builder()->where('status', \App\Enums\TagTeamStatus::Bookable->value)->count() }}
            </x-tables.header.metadata-count>
        </x-tables.header.metadata>
    </x-tables.header.container>

    <x-tables.actions-container>
        <x-buttons.add-new component="tag-teams.modals.form-modal">Add Tag Team</x-buttons.add-new>
    </x-tables.actions-container>
</x-tables.header>
