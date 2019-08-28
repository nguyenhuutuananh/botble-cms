<?php

namespace Botble\CustomField\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class CustomFieldTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * @var string
     */
    protected $view = 'plugins.custom-field::list';

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param FieldGroupInterface $fieldGroupRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        FieldGroupInterface $fieldGroupRepository
    )
    {
        $this->repository = $fieldGroupRepository;
        $this->setOption('id', 'table-custom-fields');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('title', function ($item) {
                return anchor_link(route('custom-fields.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CUSTOM_FIELD_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('custom-fields.edit', 'custom-fields.delete', $item,
                    Html::link(
                        route('custom-fields.export', ['id' => $item->id]),
                        Html::tag('i', '', ['class' => 'fa fa-download'])->toHtml(),
                        [
                            'class' => 'btn btn-icon btn-info btn-sm tip',
                            'title' => trans('plugins/custom-field::base.export'),
                        ],
                        null,
                        false
                    )->toHtml());
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by the table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model
            ->select([
                'field_groups.id',
                'field_groups.title',
                'field_groups.status',
                'field_groups.order',
                'field_groups.created_at',
            ]);
        
        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, CUSTOM_FIELD_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'field_groups.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'title'      => [
                'name'  => 'field_groups.title',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'field_groups.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'field_groups.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     * @throws \Throwable
     */
    public function buttons()
    {
        $buttons = [
            'create'             => [
                'link' => route('custom-fields.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
            'import-field-group' => [
                'link' => '#',
                'text' => view('plugins.custom-field::_partials.import')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, CUSTOM_FIELD_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('custom-fields.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getBulkChanges(): array
    {
        return [
            'field_groups.title'      => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getFieldGroups',
            ],
            'field_groups.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'field_groups.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFieldGroups()
    {
        return $this->repository->pluck('field_groups.title', 'field_groups.id');
    }
}
