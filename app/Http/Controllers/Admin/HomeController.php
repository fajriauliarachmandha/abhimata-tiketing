<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        $tickets = Ticket::with(['department', 'creator','technician'])->latest()->get();
        return Inertia::render('Admin/Home',[
            'tickets' => $tickets
        ]);
    }
}
