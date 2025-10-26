<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{
    public function index(){
        return view('dashboard.admin.ticket-status.index');
    }
    public function store(){
        return view('dashboard.admin.ticket-status.store');
    }
}
