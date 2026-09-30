<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function show(Ticket $ticket)
    {
        return Inertia::render('Admin/TicketDetail', [
            'ticket' => $ticket
        ]);
    }

     public function update(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'status' => 'In Progress',
        ]);

        return redirect()->back()->with('success', 'Ticket berhasil diperbarui.');
    }
}
