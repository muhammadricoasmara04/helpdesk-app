<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\ApplicationProblem;
use Illuminate\Http\Request;

class ApplicationProblemStaffController extends Controller
{
     public function index()
    {
        return view('dashboard.staff.problem-application.index');
    }

    public function show($id)
    {
        $problem = ApplicationProblem::with('application')->findOrFail($id);
        return view('dashboard.staff.problem-application.show', compact('problem'));
    }
}
