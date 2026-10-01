<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function history()
    {
        return Inertia::render('Admin/History',['tickets' => Ticket::with('department','users')->latest()->get() ]);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('creator', 'department', 'technician');
        $technician = Users::where('role', 'it_support')->get(['id', 'name']);
        return Inertia::render('Admin/TicketDetail', [
            'ticket' => $ticket,
            'technician' => $technician
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated=$request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);
        $ticket->update([
            'status' => 'in progress',
            'assigned_to' => $validated['assigned_to'],
        ]);

        return redirect()->back()->with('success', 'Ticket berhasil diperbarui.');
    }
}
