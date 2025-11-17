<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationStaffController extends Controller
{
    public function index()
    {
        return view('dashboard.staff.application.index');
    }

    public function show($id)
    {
        return view('dashboard.staff.application.show', compact('id'));
    }
}
