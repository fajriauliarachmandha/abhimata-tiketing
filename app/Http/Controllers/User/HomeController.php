<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Ticket $tickets){
        $tickets = Ticket::with(['department', 'creator','technician'])->latest()->get();
        return Inertia::render('User/Home', [
            'tickets' => $tickets
        ]);
    }
}
