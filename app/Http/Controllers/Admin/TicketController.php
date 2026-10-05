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
    public function history(Ticket $tickets)
    {
        $tickets = Ticket::with(['creator', 'department', 'technician'])
            ->latest()->get();
        return Inertia::render('Admin/History',[
            'tickets'=>$tickets
        ]);
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

    public function storeTracking(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'note' => 'required|string|max:500',
        ]);

        $ticket->trackings()->create([
            'user_id' => auth()->id(),
            'note' => $data['note'],
        ]);

        return back();
    }
}
