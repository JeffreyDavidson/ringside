@aware(['component', 'tableName'])
@php
    $customAttributes = $this->getBulkActionsThAttributes();
    $bulkActionsThCheckboxAttributes = $this->getBulkActionsThCheckboxAttributes();
    $theme = $this->getTheme();
@endphp

@if ($this->bulkActionsAreEnabled() && $this->hasBulkActions())
    <x-livewire-tables::table.th.plain wire:key="{{ $tableName }}-thead-bulk-actions" :displayMinimisedOnReorder="true" :$customAttributes>
        <div
            class=""
            x-data="{newSelectCount: 0, indeterminateCheckbox: false, bulkActionHeaderChecked: false}"
            x-init="$watch('selectedItems', value => indeterminateCheckbox = (value.length > 0 && value.length < paginationTotalItemCount))"
            x-cloak x-show="currentlyReorderingStatus !== true"
        >
            <input
                class="checkbox checkbox-sm"
                x-init="$watch('indeterminateCheckbox', value => $el.indeterminate = value); $watch('selectedItems', value => newSelectCount = value.length);"
                x-on:click="if(selectedItems.length == paginationTotalItemCount) { $el.indeterminate = false; $wire.clearSelected(); bulkActionHeaderChecked = false; } else { bulkActionHeaderChecked = true; $el.indeterminate = false; $wire.setAllSelected(); }"
                type="checkbox"
                :checked="selectedItems.length == paginationTotalItemCount"
                {{
                    $attributes->merge($bulkActionsThCheckboxAttributes)
                }}
            />
        </div>
    </x-livewire-tables::table.th.plain>
@endif
