@aware(['component', 'tableName', 'isTailwind', 'isBootstrap'])

@php
    $customAttributes = [
        'wrapper' => $this->getTableWrapperAttributes(),
        'table' => $this->getTableAttributes(),
        'thead' => $this->getTheadAttributes(),
        'tbody' => $this->getTbodyAttributes(),
    ];
@endphp

<div class="card-body">
    <div class="database-initialized">
        <div wire:key="{{ $tableName }}-twrap"
            {{ $attributes->merge($customAttributes['wrapper'])->class([
                    'scrollable-x-auto' => $customAttributes['wrapper']['default'] ?? true,
                ])->except('default') }}>
            <table class="table table-auto table-border" wire:key="{{ $tableName }}-table"
                {{ $attributes->merge($customAttributes['table'])->except('default') }}>
                <thead wire:key="{{ $tableName }}-thead"
                    {{ $attributes->merge($customAttributes['thead'])->except('default') }}>
                    <tr>
                        {{ $thead }}
                    </tr>
                </thead>

                <tbody wire:key="{{ $tableName }}-tbody" id="{{ $tableName }}-tbody"
                    {{ $attributes->merge($customAttributes['tbody'])->except('default') }}>
                    {{ $slot }}
                </tbody>

                @if (isset($tfoot))
                    <tfoot wire:key="{{ $tableName }}-tfoot">
                        {{ $tfoot }}
                    </tfoot>
                @endif
            </table>
        </div>
        <div
            class="card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium">
            <x-livewire-tables::pagination />
            <div class="flex items-center gap-4 order-1 md:order-2">
                @if ($this->paginationVisibilityIsEnabled())
                    <span
                        data-datatable-info="true">{{ $this->getRows->firstItem() }}-{{ $this->getRows->lastItem() }}
                        of {{ $this->getRows->count() }}</span>
                    <div class="pagination">
                        @if ($this->paginationIsEnabled())
                            {{ $this->getRows->links('livewire-tables::specific.tailwind.' . (!$this->isPaginationMethod('standard') ? 'simple-' : '') . 'pagination') }}
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
