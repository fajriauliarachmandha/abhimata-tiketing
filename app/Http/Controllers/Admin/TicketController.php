<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Tracking;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function history(Ticket $tickets)
    {
        $tickets = Ticket::with(['creator', 'department', 'technician','tracking.user'])
            ->latest()->get();
        return Inertia::render('Admin/History',[
            'tickets'=>$tickets
        ]);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('creator', 'department', 'technician','tracking.user','review');
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
        abort_if($ticket->status === 'done', 403);
        $data = $request->validate([
            'note' => 'required|string|max:500',
        ]);

        // Perbaikan 3: Menggunakan metode insert langsung yang dijamin aman
        Tracking::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $request->user()->id,
            'note'      => $data['note'],
        ]);

        return redirect()->route('admin.tickets.show', $ticket->id);
    }

    public function updateTracking(Request $request, Ticket $ticket, Tracking $tracking)
    {
        abort_if($ticket->status === 'done', 403);
        $data = $request->validate([
            'note' => 'required|string|max:500',
        ]);

        $tracking->update($data);

        return back();
    }

    public function destroy(Ticket $ticket, Tracking $tracking)
    {
        abort_if($ticket->status === 'done', 403);
        $tracking->delete();

        return redirect()->back()->with('success', 'Tracking berhasil dihapus');
    }

    public function done(Ticket $ticket)
    {
    // hanya tiket yang sedang diproses, dan hanya oleh teknisi yang ditugaskan
        abort_unless($ticket->status === 'in progress', 403);
        abort_unless((int) $ticket->assigned_to === auth()->id(), 403);

        $ticket->update(['status' => 'done']);

        return back();
    }
}
