<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil hanya tiket milik departemen user yang sedang login
        $tickets = Ticket::with('technician')
            ->where('department_id', $request->user()->department_id)
            ->latest()
            ->get();

        return Inertia::render('User/Home', [
            'tickets' => $tickets,
        ]);
    }
}
