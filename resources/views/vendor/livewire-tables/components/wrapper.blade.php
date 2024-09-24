@props(['component', 'tableName', 'primaryKey', 'isTailwind', 'isBootstrap', 'isBootstrap4', 'isBootstrap5'])
<div class="card card-grid min-w-full" wire:key="{{ $tableName }}-wrapper">
    <div {{ $attributes->merge($this->getComponentWrapperAttributes()) }}
        @if ($this->hasRefresh()) wire:poll{{ $this->getRefreshOptions() }} @endif
        @if ($this->isFilterLayoutSlideDown()) wire:ignore.self @endif>

        {{ $slot }}
    </div>
</div>
