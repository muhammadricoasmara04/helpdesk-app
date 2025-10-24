<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(){
        return view('dashboard.admin.application.index');
    }
    public function store(){
        return view('dashboard.admin.application.store');
    }
}
