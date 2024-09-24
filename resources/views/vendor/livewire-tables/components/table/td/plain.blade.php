@aware(['component', 'rowIndex', 'rowID', 'isTailwind', 'isBootstrap'])
@props([
    'column' => null,
    'customAttributes' => [],
    'displayMinimisedOnReorder' => false,
    'hideUntilReorder' => false,
])

@if ($isTailwind)
    <td x-cloak
        {{ $attributes->merge($customAttributes)->class(['hidden' => $column && $column->shouldCollapseAlways()])->class(['hidden md:table-cell' => $column && $column->shouldCollapseOnMobile()])->class(['hidden lg:table-cell' => $column && $column->shouldCollapseOnTablet()])->except('default') }}
        @if ($hideUntilReorder) x-show="reorderDisplayColumn" @endif>
        {{ $slot }}
    </td>
@endif
