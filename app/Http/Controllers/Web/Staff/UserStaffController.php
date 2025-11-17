<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserStaffController extends Controller
{
     public function index()
    {
        $users = User::latest()->get();
        $users = User::paginate(10);
        return view('dashboard.staff.users.index', compact('users'));
    }
}
