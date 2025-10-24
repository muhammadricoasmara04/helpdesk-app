<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthPageController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
}
