<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    protected function title(): string
    {
        return __($this->title);
    }
}