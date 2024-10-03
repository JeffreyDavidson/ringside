<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Rappasoft\LaravelLivewireTables\Views\Column;

trait BaseTableTrait
{
    protected array $actionLinksToDisplay = ['view' => true, 'edit' => true, 'delete' => true];

    public function configuringBaseTableTrait()
    {
        $this->setPrimaryKey('id')
            ->setColumnSelectDisabled()
            ->setSearchPlaceholder('search '.$this->databaseTableName)
            ->setPaginationEnabled()
            ->addAdditionalSelects([$this->databaseTableName.'.id as id'])
            ->setThAttributes(function (Column $column) {
                if ($column->getTitle() === __('core.actions')) {
                    $column->setColumnLabelStatusDisabled();

                    return [
                        'class' => 'w-[60px]',
                    ];
                }

                return ['class' => 'text-left text-xs leading-4 font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400'];
            })
            ->setTdAttributes(function (Column $column) {
                if ($column->getTitle() === __('core.actions')) {
                    return [
                        'class' => 'px-6 py-4 text-sm font-medium dark:text-white',
                        'default' => false,
                        'default-styling' => false,
                    ];
                }

                return ['default' => false];
            })
            ->setComponentWrapperAttributes([
                'class' => 'card card-grid min-w-full',
                'default' => false,
            ])
            ->setTableWrapperAttributes([
                'class' => 'scrollable-x-auto',
                'default' => false,
            ])
            ->setTableAttributes([
                'class' => 'table table-auto table-border',
                'default' => false,
            ])
            ->setTheadAttributes([
                'default' => false,
            ])
            ->setTbodyAttributes([
                'default' => false,
            ])
            ->setTrAttributes(function ($row, $index) {
                return ['default' => false];
            })
            ->setToolBarAttributes([
                'class' => 'card-header flex-wrap gap-2',
                'default' => false,
            ])
            ->setSearchFieldAttributes([
                'class' => 'border-0 focus:ring-0',
                'default' => false,
                'default-styling' => false,
                'default-colors' => false,
            ])
            ->setPaginationWrapperAttributes([
                'class' => 'card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium',
                'default' => false,
            ])
            ->setPerPageFieldAttributes([
                'class' => 'block select select-sm w-16 rounded-md shadow-sm transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:ring focus:ring-opacity-50',
                'default-colors' => false,
                'default-styling' => false,
            ])
            ->setPerPageAccepted([5, 10, 25, 50, 100])
            ->setLoadingPlaceholderContent('Loading')
            ->setLoadingPlaceholderEnabled();
    }

    public function appendColumns(): array
    {
        return [
            Column::make(__('core.actions'))
                ->label(
                    fn ($row, Column $column) => view('tables.columns.action-column')->with(
                        [
                            'rowId' => $row->id,
                            'path' => $this->routeBasePath,
                            'links' => $this->actionLinksToDisplay,
                        ]
                    )
                )->html(),
        ];
    }
}
