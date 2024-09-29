<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\WrestlerBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Wrestler;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Actions\Action;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlersTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'wrestlers';

    protected string $routeBasePath = 'wrestlers';

    protected string $modalPath = 'wrestlers.wrestler-modal';

    protected array $actionLinksToDisplay = ['view' => true, 'edit' => true, 'delete' => true];

    public function configure(): void
    {
        $this->setThAttributes(function (Column $column) {
            if ($column->getTitle() === __('core.actions')) {
                return [
                    'class' => 'w-[60px]',
                ];
            }

            return [];
        });

        $this->setConfigurableAreas([
            'before-wrapper' => 'wrestlers.table-pre',
        ]);

        $this->setComponentWrapperAttributes([
            'class' => 'card card-grid min-w-full',
            'default' => false
        ]);

        $this->setTableWrapperAttributes([
            'class' => 'scrollable-x-auto',
            'default' => false
        ]);

        $this->setPaginationWrapperAttributes([
            'class' => 'card-body database-initialized',
        ]);

        $this->setTableAttributes([
            'class' => 'table table-auto table-border',
            'default' => false,
        ]);

        $this->setTheadAttributes([
            'default' => false,
        ]);

        $this->setTbodyAttributes([
            'default' => false,
        ]);

        $this->setTrAttributes(function ($row, $index) {
            return ['default' => false];
        });
    }

    public function builder(): WrestlerBuilder
    {
        return Wrestler::query();
        // ->with('employments:id,started_at');
    }

    public function delete(Wrestler $model)
    {
        try {
            Gate::authorize('delete', $model);
            $model->delete();
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    public function columns(): array
    {
        return [
            Column::make(__('wrestlers.height')),
            Column::make(__('wrestlers.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('wrestlers.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('wrestlers.height'), 'height'),
            Column::make(__('wrestlers.weight'), 'weight'),
            Column::make(__('wrestlers.hometown'), 'hometown'),
            // Column::make(__('employments.start_date'), 'started_at')
            //     ->label(fn ($row, Column $column) => $row->employments->first()->started_at->format('Y-m-d')),
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

    public function actions(): array
    {
        return [
            Action::make('Create')->setWireAction('wire:click')->setWireActionDispatchParams("'openModal', { component: '".$this->modalPath."' }"),
        ];
    }
}
