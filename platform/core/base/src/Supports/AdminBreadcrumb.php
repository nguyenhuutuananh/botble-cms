<?php

namespace Botble\Base\Supports;

use Breadcrumbs;

class AdminBreadcrumb
{
    /**
     * @return string
     * @author Sang Nguyen
     * @throws \Exception
     */
    public function render(): string
    {
        return Breadcrumbs::render('main', page_title()->getTitle(false));
    }
}
