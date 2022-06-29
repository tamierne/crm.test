<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function index(){
        $name = auth()->user()->name;
        return view('admin.index', compact('name'));
    }
}
