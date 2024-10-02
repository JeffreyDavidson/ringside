@props(['direction'])

@if ($this->isTailwind)
    @switch($direction)
        @case('asc')
            <span class="sort-icon"></span>
        @break

        @case('desc')
            <span class="sort-icon"></span>
        @break

        @default
            <span class="sort-icon"></span>
    @endswitch
@else
    @switch($direction)
        @case('asc')
            <span class="sort-icon"></span>
        @break

        @case('desc')
            <span class="sort-icon"></span>
        @break

        @default
            <span class="sort-icon"></span>
    @endswitch
@endif
