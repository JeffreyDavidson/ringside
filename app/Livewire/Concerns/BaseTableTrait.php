<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Rappasoft\LaravelLivewireTables\Views\Column;

trait BaseTableTrait
{
    use HasActionColumn;

    protected array $actionLinksToDisplay = ['view' => true, 'edit' => true, 'delete' => true];

    protected bool $showActionColumn = true;

    public function configuringBaseTableTrait()
    {
        $this->setPrimaryKey('id')
            ->setColumnSelectDisabled()
            ->setSearchPlaceholder('search '.$this->databaseTableName)
            ->setSearchFieldAttributes([
                'defaults' => false,
            ])
            ->setPaginationEnabled()
            ->setDisplayPaginationDetailsEnabled()
            ->addAdditionalSelects([$this->databaseTableName.'.id as id'])
            ->setPerPageAccepted([5, 10, 25, 50, 100])
            ->setPerPageFieldAttributes([
                'class' => 'block appearance-none shadow-none font-medium text-xs leading-none h-8 ps-2.5 pe-2.5 bg-[length:14px_10px] rounded-md text-gray-700 border border-solid border-gray-300 w-16',
                'default-colors' => false,
                'default-styling' => false,
            ])
            ->setLoadingPlaceholderContent('Loading')
            ->setLoadingPlaceholderEnabled()
            ->setTableWrapperAttributes([
                'default' => false,
                'class' => 'scrollable-x-auto',
            ])
            ->setTableAttributes([
                'default' => false,
                'class' => 'w-full caption-bottom border-collapse text-left text-gray-700 font-medium text-sm border table table-auto border-0 divide-y divide-gray-300',
            ])
            ->setTheadAttributes([
                'default' => false,
                'class' => '',
            ])
            ->setThAttributes(function (Column $column) {
                return [
                    'default' => false,
                    'class' => 'py-2.5 ps-px pe-px border border-b border-gray-200 font-medium text-gray-600 text-2sm align-middle bg-[#fcfcfc] border-e-1 border-gray-200',
                ];
            })
            ->setThSortButtonAttributes(function (Column $column) {
                return [
                    'default' => false,
                    'class' => 'sort',
                ];
            })
            ->setTbodyAttributes([
                'default' => false,
                'class' => 'divide-y divide-gray-200 ',
            ])
            ->setTrAttributes(function ($row, $index) {
                return [
                    'default' => false,
                    'class' => '',
                ];
            })
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                return [
                    'default' => false,
                    'class' => '',
                ];
            });
    }

    public function appendColumns(): array
    {
        return $this->showActionColumn ? [
            $this->getDefaultActionColumn(),
        ] : [];
    }
}
