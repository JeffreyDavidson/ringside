@aware(['component', 'isTailwind', 'isBootstrap', 'isBootstrap4', 'isBootstrap5'])

@if ($this->hasConfigurableAreaFor('before-pagination'))
    @include(
        $this->getConfigurableAreaFor('before-pagination'),
        $this->getParametersForConfigurableArea('before-pagination'))
@endif

<div class="flex items-center gap-2 order-2 md:order-1">
    @if ($this->paginationIsEnabled() && $this->perPageVisibilityIsEnabled())
        <x-livewire-tables::tools.toolbar.items.pagination-dropdown />
    @endif
</div>

@if ($this->hasConfigurableAreaFor('after-pagination'))
    @include(
        $this->getConfigurableAreaFor('after-pagination'),
        $this->getParametersForConfigurableArea('after-pagination'))
@endif
