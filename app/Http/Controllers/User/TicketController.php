<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(){
        return Inertia::render('User/Ticket', [
            'tickets' => Ticket::where('created_by', Auth::id())->with(['department', 'creator', 'technician','tracking.user'])->latest()->get()
        ]);
    }

    public function store(Request $request){

        $validated=$request->validate([
            'category' => 'required|string|in:Hardware,Software,Jaringan,Printer,Lainnya',
            'priority' => 'required|string|in:Low,Medium,High,Critical',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('tickets', 'public');
        }

        Ticket::create([
            ...$validated,
            'ticket_number' => $this->generateTicketNumber(),
            'department_id' => $request->user()->department_id,
            'created_by' => $request->user()->id,
            'eta' => now()->addDays()
        ]);


        return redirect()->route('user.home')->with('success', 'Ticket created successfully.');
    }

    private function generateTicketNumber():string
    {
        do {
            $number = 'TCK-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (Ticket::where('ticket_number', $number)->exists());

        return $number;
    }

    public function show(Ticket $ticket)
    {
        abort_unless($ticket->created_by === Auth::id(), 403);
        return Inertia::render('User/TicketDetail', [
            'ticket' => $ticket->load('creator', 'department', 'technician','tracking.user','review')
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        if($ticket->created_by !== Auth::id()){
            abort(403);
        }

        $ticket->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Ticket berhasil dibatalkan.');
    }
}
