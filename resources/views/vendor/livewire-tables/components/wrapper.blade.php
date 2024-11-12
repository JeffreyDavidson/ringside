@props(['component', 'tableName', 'primaryKey', 'isTailwind', 'isBootstrap', 'isBootstrap4', 'isBootstrap5'])
<div wire:key="{{ $tableName }}-wrapper">
    <div {{ $attributes->merge($this->getComponentWrapperAttributes()) }}
        @if ($this->hasRefresh()) wire:poll{{ $this->getRefreshOptions() }} @endif
        @if ($this->isFilterLayoutSlideDown()) wire:ignore.self @endif>

        <div class="flex flex-col card-bg min-w-full rounded-xl shadow-card border border-solid border-gray-200">
            @if ($this->debugIsEnabled())
                @include('livewire-tables::includes.debug')
            @endif
            @if ($this->offlineIndicatorIsEnabled())
                @include('livewire-tables::includes.offline')
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
