<?php

namespace Botble\ACL\Tables;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class RoleTable extends TableAbstract
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
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param RoleInterface $roleRepository
     * @param UserInterface $userRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        RoleInterface $roleRepository,
        UserInterface $userRepository
    ) {
        $this->repository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->setOption('id', 'table-roles');
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
            ->editColumn('name', function ($item) {
                return anchor_link(route('roles.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('core.base.general.date_format.date'));
            })
            ->editColumn('created_by', function ($item) {
                return $item->userCreated->getFullName();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, ROLE_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('roles.edit', 'roles.delete', $item);
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
                'roles.id',
                'roles.name',
                'roles.description',
                'roles.created_at',
                'roles.created_by',
            ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, ROLE_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'          => [
                'name'  => 'roles.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-center',
            ],
            'name'        => [
                'name'  => 'roles.name',
                'title' => trans('core/base::tables.name'),
            ],
            'description' => [
                'name'  => 'roles.description',
                'title' => trans('core/base::tables.description'),
            ],
            'created_at'  => [
                'name'  => 'roles.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'created_by'  => [
                'name'  => 'roles.created_by',
                'title' => trans('core/acl::permissions.created_by'),
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
            'create' => [
                'link' => route('roles.create'),
                'text' => view('core.base::elements.tables.actions.create')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, ROLE_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('roles.delete.many'),
            'data_class' => get_class($this),
        ]);

        return $actions;
    }

    /**
     * @return mixed
     */
    public function getBulkChanges(): array
    {
        return [
            'roles.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getRoles',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->repository->pluck('roles.name', 'roles.id');
    }
}
