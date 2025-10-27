<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardUserController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Welcome User Dashboard']);
    }
}
