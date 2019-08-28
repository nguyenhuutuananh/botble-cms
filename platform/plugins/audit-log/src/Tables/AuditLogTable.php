<?php

namespace Botble\AuditLog\Tables;

use Botble\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class AuditLogTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * AuditLogTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param AuditLogInterface $auditLogRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AuditLogInterface $auditLogRepository)
    {
        $this->repository = $auditLogRepository;
        $this->setOption('id', 'table-audit-logs');
        parent::__construct($table, $urlGenerator);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('action', function ($history) {
                return view('plugins.audit-log::activity-line', compact('history'))->render();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, AUDIT_LOG_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions(null, 'audit-log.delete', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $query = $model
            ->with(['user'])
            ->select('audit_history.*');

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, AUDIT_LOG_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'audit_history.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'action'     => [
                'name'  => 'audit_history.action',
                'title' => trans('plugins/audit-log::history.action'),
                'class' => 'text-left',
            ],
            'user_agent' => [
                'name'  => 'audit_history.user_agent',
                'title' => trans('plugins/audit-log::history.user_agent'),
                'class' => 'text-left',
            ],
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function buttons()
    {
        return apply_filters(BASE_FILTER_TABLE_BUTTONS, [], AUDIT_LOG_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = [];

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('audit-log.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }
}
