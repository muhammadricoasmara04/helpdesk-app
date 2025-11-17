<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationStaffController extends Controller
{
    public function index()
    {
        $organizations = Organization::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.staff.organization.index', compact('organizations'));
    }
    public function show($id)
    {
        $organization = Organization::findOrFail($id);
        return view('dashboard.staff.organization.show', compact('organization'));
    }
}
