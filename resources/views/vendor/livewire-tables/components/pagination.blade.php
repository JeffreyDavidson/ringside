@aware(['component','isTailwind','isBootstrap','isBootstrap4','isBootstrap5'])

@includeWhen(
    $this->hasConfigurableAreaFor('before-pagination'),
    $this->getConfigurableAreaFor('before-pagination'),
    $this->getParametersForConfigurableArea('before-pagination')
)

@if ($this->isTailwind)
    <div {{ $this->getPaginationWrapperAttributesBag() }}>
        @if ($this->paginationVisibilityIsEnabled())
            <div class="flex items-center gap-2 order-2 md:order-1">
                @if ($this->paginationIsEnabled() && $this->perPageVisibilityIsEnabled())
                    Show <x-livewire-tables::tools.toolbar.items.pagination-dropdown /> per page
                @endif
            </div>
            <div class="flex items-center gap-4 order-1 md:order-2">
                @if ($this->paginationIsEnabled() && $this->isPaginationMethod('standard') && $this->getRows->lastPage() > 1)
                    @if($this->showPaginationDetails())
                        <span>
                            @lang('livewire-tables::Showing')
                            {{ $this->getRows->firstItem() }}
                            @lang('livewire-tables::to')
                            {{ $this->getRows->lastItem() }}
                            @lang('livewire-tables::of')
                            {{ $this->paginationTotalItemCount }}
                            @lang('livewire-tables::results')
                        </span>
                    @endif
                @elseif ($this->paginationIsEnabled() && $this->isPaginationMethod('simple'))
                    @if($this->showPaginationDetails())
                        <span>@lang('livewire-tables::Showing')</span>
                        <span class="font-medium">{{ $this->getRows->firstItem() }}</span>
                        <span>@lang('livewire-tables::to')</span>
                        <span class="font-medium">{{ $this->getRows->lastItem() }}</span>
                    @endif
                @elseif ($this->paginationIsEnabled() && $this->isPaginationMethod('cursor'))
                @else
                    <p class="total-pagination-results text-sm text-gray-700 leading-5 dark:text-white">
                        @lang('livewire-tables::Showing')
                        <span class="font-medium">{{ $this->getRows->count() }}</span>
                        @lang('livewire-tables::results')
                    </p>
                @endif
                <div class="pagination">
                    @if ($this->paginationIsEnabled())
                        {{ $this->getRows->links('livewire-tables::specific.tailwind.'.(!$this->isPaginationMethod('standard') ? 'simple-' : '').'pagination') }}
                    @endif
                </div>
            </div>
        @endif
    </div>
@elseif ($this->isBootstrap4)
    <div {{ $this->getPaginationWrapperAttributesBag() }}>
        @if ($this->paginationVisibilityIsEnabled())
            @if ($this->paginationIsEnabled() && $this->isPaginationMethod('standard') && $this->getRows->lastPage() > 1)
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $this->getRows->links('livewire-tables::specific.bootstrap-4.pagination') }}
                    </div>

                    <div class="col-12 col-md-6 text-center text-md-right text-muted">
                        @if($this->showPaginationDetails())
                            <span>@lang('livewire-tables::Showing')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->firstItem() : 0 }}</strong>
                            <span>@lang('livewire-tables::to')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->lastItem() : 0 }}</strong>
                            <span>@lang('livewire-tables::of')</span>
                            <strong><span x-text="paginationTotalItemCount"></span></strong>
                            <span>@lang('livewire-tables::results')</span>
                        @endif
                    </div>
                </div>
            @elseif ($this->paginationIsEnabled() && $this->isPaginationMethod('simple'))
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $this->getRows->links('livewire-tables::specific.bootstrap-4.simple-pagination') }}
                    </div>

                    <div class="col-12 col-md-6 text-center text-md-right text-muted">
                        @if($this->showPaginationDetails())
                            <span>@lang('livewire-tables::Showing')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->firstItem() : 0 }}</strong>
                            <span>@lang('livewire-tables::to')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->lastItem() : 0 }}</strong>
                        @endif
                    </div>
                </div>
            @elseif ($this->paginationIsEnabled() && $this->isPaginationMethod('cursor'))
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $this->getRows->links('livewire-tables::specific.bootstrap-4.simple-pagination') }}
                    </div>
                </div>
            @else
                <div class="row mt-3">
                    <div class="col-12 text-muted">
                        @lang('livewire-tables::Showing')
                        <strong>{{ $this->getRows->count() }}</strong>
                        @lang('livewire-tables::results')
                    </div>
                </div>
            @endif
        @endif
    </div>
@elseif ($this->isBootstrap5)
    <div {{ $this->getPaginationWrapperAttributesBag() }} >
        @if ($this->paginationVisibilityIsEnabled())
            @if ($this->paginationIsEnabled() && $this->isPaginationMethod('standard') && $this->getRows->lastPage() > 1)
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $this->getRows->links('livewire-tables::specific.bootstrap-4.pagination') }}
                    </div>
                    <div class="col-12 col-md-6 text-center text-md-end text-muted">
                        @if($this->showPaginationDetails())
                            <span>@lang('livewire-tables::Showing')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->firstItem() : 0 }}</strong>
                            <span>@lang('livewire-tables::to')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->lastItem() : 0 }}</strong>
                            <span>@lang('livewire-tables::of')</span>
                            <strong><span x-text="paginationTotalItemCount"></span></strong>
                            <span>@lang('livewire-tables::results')</span>
                        @endif
                    </div>
                </div>
            @elseif ($this->paginationIsEnabled() && $this->isPaginationMethod('simple'))
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $this->getRows->links('livewire-tables::specific.bootstrap-4.simple-pagination') }}
                    </div>
                    <div class="col-12 col-md-6 text-center text-md-end text-muted">
                        @if($this->showPaginationDetails())
                            <span>@lang('livewire-tables::Showing')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->firstItem() : 0 }}</strong>
                            <span>@lang('livewire-tables::to')</span>
                            <strong>{{ $this->getRows->count() ? $this->getRows->lastItem() : 0 }}</strong>
                        @endif
                    </div>
                </div>
            @elseif ($this->paginationIsEnabled() && $this->isPaginationMethod('cursor'))
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $this->getRows->links('livewire-tables::specific.bootstrap-4.simple-pagination') }}
                    </div>
                </div>
            @else
                <div class="row mt-3">
                    <div class="col-12 text-muted">
                        @lang('livewire-tables::Showing')
                        <strong>{{ $this->getRows->count() }}</strong>
                        @lang('livewire-tables::results')
                    </div>
                </div>
            @endif
        @endif
    </div>
@endif

@includeWhen(
    $this->hasConfigurableAreaFor('after-pagination'),
    $this->getConfigurableAreaFor('after-pagination'),
    $this->getParametersForConfigurableArea('after-pagination')
)
