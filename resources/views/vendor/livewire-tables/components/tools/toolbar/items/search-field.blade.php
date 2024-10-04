@aware(['component', 'tableName', 'isTailwind', 'isBootstrap'])

<div @class([
    'mb-3 mb-md-0 input-group' => $this->isBootstrap,
    'flex' => $this->isTailwind,
])>
    <label class="input input-sm">
        <i class="ki-filled ki-magnifier"></i>
        <input wire:model{{ $this->getSearchOptions() }}="search" placeholder="{{ $this->getSearchPlaceholder() }}"
            type="text"
            {{ $attributes->merge($this->getSearchFieldAttributes())->class([
                    'block w-full rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-none rounded-l-md focus:ring-0 focus:border-gray-300' =>
                        $this->isTailwind &&
                        $this->hasSearch() &&
                        ($this->getSearchFieldAttributes()['default'] ??
                            (true || $this->getSearchFieldAttributes()['default-styling'] ?? true)),
                    'block w-full rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 rounded-md focus:ring focus:ring-opacity-50' =>
                        $this->isTailwind &&
                        !$this->hasSearch() &&
                        ($this->getSearchFieldAttributes()['default'] ??
                            (true || $this->getSearchFieldAttributes()['default-styling'] ?? true)),
                    'border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:border-gray-300' =>
                        $this->isTailwind &&
                        $this->hasSearch() &&
                        ($this->getSearchFieldAttributes()['default'] ??
                            (true || $this->getSearchFieldAttributes()['default-colors'] ?? true)),
                    'border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:border-indigo-300 focus:ring-indigo-200' =>
                        $this->isTailwind &&
                        !$this->hasSearch() &&
                        ($this->getSearchFieldAttributes()['default'] ??
                            (true || $this->getSearchFieldAttributes()['default-colors'] ?? true)),

                    'form-control' => $this->isBootstrap && $this->getSearchFieldAttributes()['default'] ?? true,
                ])->except(['default', 'default-styling', 'default-colors']) }} />

        @if ($this->hasSearch())
            @if ($this->isTailwind)
                <button
                    class="inline-flex items-center cursor-pointer leading-4 rounded-md border border-solid w-4 h-4 justify-center shrink-0 p-0 gap-0 font-medium text-base btn-light"
                    wire:click="clearSearch">
                    <i class="ki-outline ki-cross"></i>
                </button>
            @else
                <x-heroicon-m-x-mark class="laravel-livewire-tables-btn-smaller" />
            @endif
        @endif
    </label>
</div>
