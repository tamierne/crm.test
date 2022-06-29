<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function index(){
        return view('admin.index');
    }
}
