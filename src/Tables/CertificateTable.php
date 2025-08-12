<?php

namespace Whozidis\HallOfFame\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Whozidis\HallOfFame\Models\Certificate;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\DateTimeColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class CertificateTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Certificate::class)
            ->addActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('certificate_id', function (Certificate $item) {
                return Html::tag('code', $item->certificate_id, ['class' => 'text-primary']);
            })
            ->editColumn('vulnerability_report_id', function (Certificate $item) {
                if ($item->vulnerabilityReport) {
                    return Html::link(
                        route('hall-of-fame.vulnerability-reports.edit', $item->vulnerability_report_id),
                        $item->vulnerabilityReport->title ?: 'Report #' . $item->vulnerability_report_id,
                        ['target' => '_blank']
                    );
                }
                return __('N/A');
            })
            ->editColumn('user_id', function (Certificate $item) {
                if ($item->user) {
                    return $item->user->name;
                }
                return __('N/A');
            })
            ->editColumn('has_pdf', function (Certificate $item) {
                if ($item->hasPdf()) {
                    return Html::tag('span', __('Yes'), ['class' => 'label label-success']);
                }
                return Html::tag('span', __('No'), ['class' => 'label label-warning']);
            })
            ->editColumn('has_signed_pdf', function (Certificate $item) {
                if ($item->hasSignedPdf()) {
                    return Html::tag('span', __('Yes'), ['class' => 'label label-success']);
                }
                return Html::tag('span', __('No'), ['class' => 'label label-warning']);
            })
            ->addColumn('has_pdf', function (Certificate $item) {
                if ($item->hasPdf()) {
                    return Html::tag('span', __('Yes'), ['class' => 'label label-success']);
                }
                return Html::tag('span', __('No'), ['class' => 'label label-warning']);
            })
            ->addColumn('has_signed_pdf', function (Certificate $item) {
                if ($item->hasSignedPdf()) {
                    return Html::tag('span', __('Yes'), ['class' => 'label label-success']);
                }
                return Html::tag('span', __('No'), ['class' => 'label label-warning']);
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
                'certificate_id',
                'vulnerability_report_id',
                'user_id',
                'issued_at',
                'status',
                'created_at',
            ])
            ->with(['vulnerabilityReport', 'user']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('certificate_id')
                ->title(__('Certificate ID'))
                ->searchable(false)
                ->sortable(),
            Column::make('vulnerability_report_id')
                ->title(__('Report'))
                ->searchable(false)
                ->sortable(),
            Column::make('user_id')
                ->title(__('User'))
                ->searchable(false)
                ->sortable(),
            DateTimeColumn::make('issued_at')
                ->title(__('Issued At')),
            Column::make('has_pdf')
                ->title(__('PDF'))
                ->searchable(false)
                ->sortable(false),
            Column::make('has_signed_pdf')
                ->title(__('Signed'))
                ->searchable(false)
                ->sortable(false),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(BaseHelper::getAdminPrefix() . '/hall-of-fame/certificates/create', 'hall-of-fame.certificates.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('hall-of-fame.certificates.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'status' => [
                'title' => __('Status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required',
            ],
        ];
    }
}
