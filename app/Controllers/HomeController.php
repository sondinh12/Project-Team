<?php

namespace App\Controllers;

use App\Common\Blade;

class HomeController
{
    public function index()
    {

        Blade::render('admin.layouts.layout');
    }
}
