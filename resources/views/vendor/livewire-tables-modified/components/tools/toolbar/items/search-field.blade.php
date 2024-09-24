@aware(['component', 'tableName', 'isTailwind', 'isBootstrap'])

<div class="flex">
    <label class="input input-sm">
        <i class="ki-filled ki-magnifier"></i>
        <input wire:model{{ $this->getSearchOptions() }}="search" placeholder="{{ $this->getSearchPlaceholder() }}"
            type="text" {{ $attributes->merge($this->getSearchFieldAttributes())->except('default') }} />
    </label>
</div>
