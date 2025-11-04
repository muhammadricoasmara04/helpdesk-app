<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketPriorityController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.ticket-priority.index');
    }

    public function store()
    {
        return view('dashboard.admin.ticket-priority.store');
    }
    
}
