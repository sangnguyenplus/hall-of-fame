<?php

namespace Whozidis\HallOfFame\Tables;

use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Whozidis\HallOfFame\Models\Researcher;

class ResearchersTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Researcher::class)
            ->addActions([
                EditAction::make()
                    ->route('researchers.edit'),
                DeleteAction::make()
                    ->route('researchers.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function (Researcher $item) {
                return Html::link(route('researchers.edit', $item->id), $item->name);
            })
            ->editColumn('email', function (Researcher $item) {
                return $item->email;
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'email',
                'website',
                'twitter',
                'github',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('name')
                ->title(trans('core/base::tables.name'))
                ->sortable(),
            Column::make('email')
                ->title(trans('core/base::tables.email'))
                ->sortable(),
            Column::make('website')
                ->title('Website')
                ->sortable(),
            Column::make('twitter')
                ->title('Twitter')
                ->sortable(),
            Column::make('github')
                ->title('GitHub')
                ->sortable(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('researchers.create'), 'researchers.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('researchers.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'email' => [
                'title' => trans('core/base::tables.email'),
                'type' => 'text',
                'validate' => 'required|email',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
