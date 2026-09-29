<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(){
        return Inertia::render('User/Ticket');
    }

    public function store(Request $request){
        $validated=$request->validate([
            'category' => 'required|string|in:Hardware,Software,Jaringan,Printer,Lainnya',
            'priority' => 'required|string|in:Low,Medium,High,Critical',
            'ticket_number' => 'required|unique:tickets',
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
        ]);


        return redirect()->back()->with('success', 'Ticket created successfully.');
    }

    private function generateTicketNumber():string
    {
        do {
            $number = 'TCK-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (Ticket::where('ticket_number', $number)->exists());

        return $number;
    }
}
