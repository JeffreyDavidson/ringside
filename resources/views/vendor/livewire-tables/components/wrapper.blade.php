@props(['component', 'tableName', 'primaryKey', 'isTailwind', 'isBootstrap','isBootstrap4', 'isBootstrap5'])
<div wire:key="{{ $tableName }}-wrapper" >
    <div {{ $attributes->merge($this->getComponentWrapperAttributes()) }}
        @if ($this->hasRefresh()) wire:poll{{ $this->getRefreshOptions() }} @endif
        @if ($this->isFilterLayoutSlideDown()) wire:ignore.self @endif>

        <x-card class="card-grid min-w-full">
        @if ($this->debugIsEnabled())
            @include('livewire-tables::includes.debug')
        @endif
        @if ($this->offlineIndicatorIsEnabled())
            @include('livewire-tables::includes.offline')
        @endif

            {{ $slot }}
        </x-card>
    </div>
</div>
