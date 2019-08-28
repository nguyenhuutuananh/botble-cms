<?php

namespace Botble\ACL\Tables;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class SuperUserTable extends TableAbstract
{

    /**
     * @var string
     */
    protected $view = 'core.acl::users.super-user';

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, UserInterface $userRepostiory)
    {
        $this->repository = $userRepostiory;
        $this->setOption('id', 'table-super-users');
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
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, SUPER_USER_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return view('core.acl::users.partials.delete', compact('item'))->render();
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
                'users.id',
                'users.username',
                'users.email',
                'users.last_login',
            ])
            ->where(['users.super_user' => 1]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, SUPER_USER_MODULE_SCREEN_NAME));
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
                'name'  => 'users.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'username'   => [
                'name'  => 'users.username',
                'title' => trans('core/acl::users.username'),
            ],
            'email'      => [
                'name'  => 'users.email',
                'title' => trans('core/base::tables.email'),
            ],
            'last_login' => [
                'name'  => 'users.last_login',
                'title' => trans('core/base::tables.last_login'),
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
            'add-supper' => [
                'link' => '#',
                'text' => view('core.acl::users.partials.add-super')->render(),
            ],
        ];

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, SUPER_USER_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        $actions = parent::bulkActions();

        $actions['delete-many'] = view('core.table::partials.delete', [
            'href'       => route('users-supers.delete.many'),
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
            'users.username'   => [
                'title'    => trans('core/acl::users.username'),
                'type'     => 'text',
                'validate' => 'required|max:120',
                'callback' => 'getUserNames',
            ],
            'users.email'      => [
                'title'    => trans('core/base::tables.email'),
                'type'     => 'text',
                'validate' => 'required|max:120|email',
                'callback' => 'getEmails',
            ],
            'users.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getUserNames()
    {
        return $this->repository->pluck('users.username', 'users.id');
    }

    /**
     * @return array
     */
    public function getEmails()
    {
        return $this->repository->pluck('users.email', 'users.id');
    }
}
