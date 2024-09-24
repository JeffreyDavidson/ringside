@aware(['component', 'tableName','primaryKey'])
@props(['row', 'rowIndex'])

@php
    $customAttributes = $this->getBulkActionsTdAttributes();
    $bulkActionsTdCheckboxAttributes = $this->getBulkActionsTdCheckboxAttributes();
    $theme = $this->getTheme();
@endphp

@if ($this->bulkActionsAreEnabled() && $this->hasBulkActions())
    <x-livewire-tables::table.td.plain wire:key="{{ $tableName }}-tbody-td-bulk-actions-td-{{ $row->{$primaryKey} }}" :displayMinimisedOnReorder="true"  :$customAttributes>
        <div @class([
            'inline-flex rounded-md shadow-sm' => $theme === 'tailwind',
            'form-check' => $theme === 'bootstrap-5',
        ])>
            <input
                class="checkbox checkbox-sm"
                x-cloak x-show="!currentlyReorderingStatus"
                x-model="selectedItems"
                wire:key="{{ $tableName . 'selectedItems-'.$row->{$primaryKey} }}"
                wire:loading.attr.delay="disabled"
                value="{{ $row->{$primaryKey} }}"
                type="checkbox"
                {{
                    $attributes->merge($bulkActionsTdCheckboxAttributes)
                }}
            />
        </div>
    </x-livewire-tables::table.td.plain>
@endif
