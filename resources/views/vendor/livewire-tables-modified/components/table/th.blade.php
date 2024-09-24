@aware(['component', 'tableName', 'isTailwind', 'isBootstrap'])
@props(['column', 'index'])

@php
    $attributes = $attributes->merge(['wire:key' => $tableName . '-header-col-' . $column->getSlug()]);
    $customAttributes = $this->getThAttributes($column);
    $customSortButtonAttributes = $this->getThSortButtonAttributes($column);
    $direction = $column->hasField()
        ? $this->getSort($column->getColumnSelectName())
        : $this->getSort($column->getSlug()) ?? null;
@endphp

<th scope="col"
    {{ $attributes->merge($customAttributes)->class(['hidden' => $column->shouldCollapseAlways()])->class(['hidden md:table-cell' => $column->shouldCollapseOnMobile()])->class(['hidden lg:table-cell' => $column->shouldCollapseOnTablet()])->except('default') }}>
    @if ($column->getColumnLabelStatus())
        @unless ($this->sortingIsEnabled() && ($column->isSortable() || $column->getSortCallback()))
            {{ $column->getTitle() }}
        @else
            <span wire:click="sortBy('{{ $column->isSortable() ? $column->getColumnSelectName() : $column->getSlug() }}')"
                {{ $attributes->merge($customSortButtonAttributes)->class(['sort', 'desc' => $direction === 'desc', 'asc' => $direction === 'asc'])->except(['default', 'wire:key']) }}>
                <span
                    {{ $attributes->merge($customAttributes)->class([
                            'text-left text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400' =>
                                $customAttributes['default'] ?? true,
                        ])->class(['hidden' => $column->shouldCollapseAlways()])->class(['hidden md:table-cell' => $column->shouldCollapseOnMobile()])->class(['hidden lg:table-cell' => $column->shouldCollapseOnTablet()])->except('default') }}>{{ $column->getTitle() }}</span>

                <span class="sort-icon"></span>
            </span>
        @endunless
    @endif
</th>
