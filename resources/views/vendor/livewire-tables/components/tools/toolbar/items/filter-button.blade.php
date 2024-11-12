@aware(['component', 'tableName', 'isTailwind', 'isBootstrap', 'isBootstrap4', 'isBootstrap5'])
@props([])

<div @if ($this->isFilterLayoutPopover()) x-data="{ filterPopoverOpen: false }"
        x-on:keydown.escape.stop="if (!this.childElementOpen) { filterPopoverOpen = false }"
        x-on:mousedown.away="if (!this.childElementOpen) { filterPopoverOpen = false }" @endif
    @class([
        'btn-group d-block d-md-inline' => $this->isBootstrap,
        'flex flex-wrap gap-2.5' => $this->isTailwind,
    ])>

    <button type="button" @class([
        'btn dropdown-toggle d-block w-100 d-md-inline' => $this->isBootstrap,
        'inline-flex items-center cursor-pointer leading-4 rounded-md border border-solid h-8 ps-5 pe-5 font-medium text-xs gap-[.275rem] text-primary-default bg-primary-light border-primary-clarity hover:bg-primary-default hover:text-primary-inverse hover:border-primary' =>
            $this->isTailwind,
    ])
        @if ($this->isFilterLayoutPopover()) x-on:click="filterPopoverOpen = !filterPopoverOpen"
            aria-haspopup="true"
            x-bind:aria-expanded="filterPopoverOpen"
            aria-expanded="true" @endif
        @if ($this->isFilterLayoutSlideDown()) x-on:click="filtersOpen = !filtersOpen" @endif>
        <i class="ki-filled ki-setting-4"></i>
        @lang('Filters')

        @if ($count = $this->getFilterBadgeCount())
            <span @class([
                'badge badge-info' => $this->isBootstrap,
                'ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-indigo-100 text-indigo-800 capitalize dark:bg-indigo-200 dark:text-indigo-900' =>
                    $this->isTailwind,
            ])>
                {{ $count }}
            </span>
        @endif
    </button>

    @if ($this->isFilterLayoutPopover())
        <x-livewire-tables::tools.toolbar.items.filter-popover />
    @endif

</div>
