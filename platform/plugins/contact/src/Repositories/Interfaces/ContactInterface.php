<?php

namespace Botble\Contact\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface ContactInterface extends RepositoryInterface
{
    /**
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function getUnread($select = ['*']);

    /**
     * @return int
     * @author Sang Nguyen
     */
    public function countUnread();
}
