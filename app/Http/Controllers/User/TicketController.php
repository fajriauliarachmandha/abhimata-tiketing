<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TicketController extends Controller
{
    // Daftar tiket yang dibuat oleh user yang sedang login
    public function index(Request $request)
    {
        $tickets = Ticket::where('created_by', $request->user()->id)
            ->with(['department', 'creator', 'technician', 'tracking.user'])
            ->latest()
            ->get();

        return Inertia::render('User/Ticket', [
            'tickets' => $tickets,
        ]);
    }

    // Simpan tiket baru dari form pengajuan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'    => 'required|string|in:Hardware,Software,Jaringan,Printer,Lainnya',
            'priority'    => 'required|string|in:Low,Medium,High,Critical',
            'description' => 'required|string',
            'photo'       => 'nullable|image|max:5120', // maksimal 5 MB
        ]);

        // Kalau ada foto, simpan ke storage/app/public/tickets
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('tickets', 'public');
        }

        Ticket::create([
            ...$validated,
            'ticket_number' => $this->generateTicketNumber(),
            'department_id' => $request->user()->department_id,
            'created_by'    => $request->user()->id,
            'eta'           => now()->addDay(), // target selesai: 1 hari dari sekarang
        ]);

        return redirect()->route('user.home')->with('success', 'Ticket created successfully.');
    }

    // Detail satu tiket (hanya untuk pembuatnya)
    public function show(Request $request, Ticket $ticket)
    {
        $this->abortUnlessOwner($request, $ticket);

        return Inertia::render('User/TicketDetail', [
            'ticket' => $ticket->load('creator', 'department', 'technician', 'tracking.user', 'review'),
        ]);
    }

    // Batalkan tiket (hanya untuk pembuatnya)
    public function update(Request $request, Ticket $ticket)
    {
        $this->abortUnlessOwner($request, $ticket);

        $ticket->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Ticket berhasil dibatalkan.');
    }

    // Buat nomor tiket unik, contoh: TCK-20261011114800-A1B2
    private function generateTicketNumber(): string
    {
        do {
            $number = 'TCK-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (Ticket::where('ticket_number', $number)->exists());

        return $number;
    }

    // Hentikan dengan error 403 kalau tiket bukan milik user yang sedang login
    private function abortUnlessOwner(Request $request, Ticket $ticket): void
    {
        abort_unless($ticket->created_by === $request->user()->id, 403);
    }
}
