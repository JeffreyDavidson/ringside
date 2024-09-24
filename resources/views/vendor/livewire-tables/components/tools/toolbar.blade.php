@aware(['component', 'tableName', 'isTailwind', 'isBootstrap'])
@props([])

<h3 class="card-title font-medium text-sm">
    Showing
</h3>

<div class="flex flex-wrap gap-2 lg:gap-5">
    @if ($this->reorderIsEnabled())
        <x-livewire-tables::tools.toolbar.items.reorder-buttons />
    @endif

    @if ($this->searchIsEnabled() && $this->searchVisibilityIsEnabled())
        <x-livewire-tables::tools.toolbar.items.search-field />
    @endif

    @if ($this->hasActions && $this->showActionsInToolbar && $this->getActionsPosition == 'left')
        <x-livewire-tables::includes.actions />
    @endif

    <div class="flex flex-wrap gap-2.5" x-cloak>
        @if ($this->hasConfigurableAreaFor('toolbar-right-start'))
            @include(
                $this->getConfigurableAreaFor('toolbar-right-start'),
                $this->getParametersForConfigurableArea('toolbar-right-start'))
        @endif

        @if ($this->hasActions && $this->showActionsInToolbar && $this->getActionsPosition == 'right')
            <x-livewire-tables::includes.actions />
        @endif

        @if ($this->showBulkActionsDropdownAlpine() && $this->shouldAlwaysHideBulkActionsDropdownOption != true)
            <x-livewire-tables::tools.toolbar.items.bulk-actions />
        @endif

        @if ($this->columnSelectIsEnabled())
            <x-livewire-tables::tools.toolbar.items.column-select />
        @endif

        @if ($this->filtersAreEnabled() && $this->filtersVisibilityIsEnabled() && $this->hasVisibleFilters())
            <x-livewire-tables::tools.toolbar.items.filter-button />
        @endif

        @if ($this->hasConfigurableAreaFor('toolbar-right-end'))
            @include(
                $this->getConfigurableAreaFor('toolbar-right-end'),
                $this->getParametersForConfigurableArea('toolbar-right-end'))
        @endif

        @if (
            $this->filtersAreEnabled() &&
                $this->filtersVisibilityIsEnabled() &&
                $this->hasVisibleFilters() &&
                $this->isFilterLayoutSlideDown())

            <x-livewire-tables::tools.toolbar.items.filter-slidedown />
        @endif
    </div>
</div>
