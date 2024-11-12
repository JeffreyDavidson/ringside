@aware(['component', 'tableName', 'isTailwind', 'isBootstrap'])

<div @class([
    'mb-3 mb-md-0 input-group' => $this->isBootstrap,
    'flex' => $this->isTailwind,
])>
    <label
        class="flex items-center gap-1.5 w-full leading-4 bg-light-active rounded-md border border-solid border-gray-300 appearance-none shadow-none outline-none text-gray-600 font-medium text-xs h-8 ps-2.5 pe-2.5">
        <i class="ki-filled ki-magnifier"></i>
        <input wire:model{{ $this->getSearchOptions() }}="search" placeholder="{{ $this->getSearchPlaceholder() }}"
            type="text"
            {{ $attributes->merge($this->getSearchFieldAttributes())->class([
                    'block w-full border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-none rounded-l-md focus:ring-0 focus:border-gray-300' =>
                        $this->isTailwind && $this->hasSearch() && $this->getSearchFieldAttributes()['default'] ?? true,
                    'block w-full border-gray-300 rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 dark:bg-gray-700 dark:text-white dark:border-gray-600 rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50' =>
                        $this->isTailwind && !$this->hasSearch() && $this->getSearchFieldAttributes()['default'] ?? true,
                    'form-control' => $this->isBootstrap && $this->getSearchFieldAttributes()['default'] ?? true,
                ])->except('default') }} />
    </label>
</div>
