<?php

namespace App\Http\Controllers;

use Rap2hpoutre\LaravelLogViewer\LogViewerController as PackageLogViewerController;

class LogViewerController extends PackageLogViewerController
{
    public function index()
    {
        $this->request = request();
        return parent::index();
    }
}