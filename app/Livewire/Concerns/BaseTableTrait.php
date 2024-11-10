<?php

namespace App\Livewire\Concerns;

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
            ->setPaginationEnabled()
            ->addAdditionalSelects([$this->databaseTableName.'.id as id'])
            ->setPerPageAccepted([5, 10, 25, 50, 100])
            ->setLoadingPlaceholderContent('Loading')
            ->setLoadingPlaceholderEnabled()
            ->setTableWrapperAttributes([
                'default' => false,
                'class' => 'scrollable-x-auto',
            ])
            ->setTableAttributes([
                'default' => false,
                'class' => 'w-full caption-bottom border-collapse text-left text-gray-700 font-medium text-sm border table table-auto',
            ])
            ->setTheadAttributes([
                'default' => false,
                'class' => '',
            ])
            ->setTbodyAttributes([
                'default' => false,
                'class' => '',
            ]);
    }

    public function appendColumns(): array
    {
        return $this->showActionColumn ? [
            $this->getDefaultActionColumn(),
        ] : [];
    }
}
