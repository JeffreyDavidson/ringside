@aware(['component', 'tableName','isTailwind','isBootstrap'])

@if ($this->bulkActionsAreEnabled() && $this->hasBulkActions())
    @php
        $colspan = $this->getColspanCount();
        $selectAll = $this->selectAllIsEnabled();
        $simplePagination = $this->isPaginationMethod('simple');
    @endphp

    @if ($isTailwind)
        <x-livewire-tables::table.tr.plain
            x-cloak x-show="selectedItems.length > 0 && !currentlyReorderingStatus"
            wire:key="{{ $tableName }}-bulk-select-message"
            class="bg-indigo-50 dark:bg-gray-900 dark:text-white"
        >
        </x-livewire-tables::table.tr.plain>
    @elseif ($isBootstrap)
        <x-livewire-tables::table.tr.plain
            x-cloak x-show="selectedItems.length > 0 && !currentlyReorderingStatus"
            wire:key="{{ $tableName }}-bulk-select-message"
        >
            <x-livewire-tables::table.td.plain :colspan="$colspan">
                <template x-if="selectedItems.length == paginationTotalItemCount || selectAllStatus">
                    <div wire:key="{{ $tableName }}-all-selected">
                        <span>
                            @lang('You are currently selecting all')
                            @if(!$simplePagination) <strong><span x-text="paginationTotalItemCount"></span></strong> @endif
                            @lang('rows').
                        </span>

                        <button
                            x-on:click="clearSelected"
                            wire:loading.attr="disabled"
                            type="button"
                            class="btn btn-primary btn-sm"
                        >
                            @lang('Deselect All')
                        </button>
                    </div>
                </template>

                <template x-if="selectedItems.length !== paginationTotalItemCount && !selectAllStatus">
                    <div wire:key="{{ $tableName }}-some-selected">
                        <span>
                            @lang('You have selected')
                            <strong><span x-text="selectedItems.length"></span></strong>
                            @lang('rows, do you want to select all')
                            @if(!$simplePagination) <strong><span x-text="paginationTotalItemCount"></span></strong> @endif
                        </span>

                        <button
                            x-on:click="selectAllOnPage"
                            wire:loading.attr="disabled"
                            type="button"
                            class="btn btn-primary btn-sm"
                        >
                            @lang('Select All On Page')
                        </button>&nbsp;

                        <button
                            x-on:click="setAllSelected()"
                            wire:loading.attr="disabled"
                            type="button"
                            class="btn btn-primary btn-sm"
                        >
                            @lang('Select All')
                        </button>

                        <button
                            x-on:click="clearSelected"
                            wire:loading.attr="disabled"
                            type="button"
                            class="btn btn-primary btn-sm"
                        >
                            @lang('Deselect All')
                        </button>
                    </div>
                </template>
            </x-livewire-tables::table.td.plain>
        </x-livewire-tables::table.tr.plain>
    @endif
@endif
