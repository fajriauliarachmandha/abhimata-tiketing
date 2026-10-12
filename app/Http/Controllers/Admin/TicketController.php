<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Tracking;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TicketController extends Controller
{
    // Daftar semua tiket (halaman "Data Tiket")
    public function history()
    {
        return Inertia::render('Admin/History', [
            'tickets' => Ticket::with(['creator', 'department', 'technician', 'tracking.user'])
                ->latest()
                ->get(),
        ]);
    }

    // Detail satu tiket, beserta daftar teknisi untuk dipilih
    public function show(Ticket $ticket)
    {
        return Inertia::render('Admin/TicketDetail', [
            'ticket'      => $ticket->load('creator', 'department', 'technician', 'tracking.user', 'review'),
            'technicians' => Users::where('role', 'it_support')->get(['id', 'name']),
        ]);
    }

    // Memproses tiket: pilih teknisi, lalu status menjadi "in progress"
    public function process(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            // Teknisi harus user yang ada DAN berole it_support
            'assigned_to' => ['required', Rule::exists('users', 'id')->where('role', 'it_support')],
        ]);

        $ticket->update([
            'status'      => 'in progress',
            'assigned_to' => $validated['assigned_to'],
        ]);

        return back()->with('success', 'Ticket berhasil diperbarui.');
    }

    // Menyelesaikan tiket: hanya tiket "in progress", dan hanya oleh teknisi yang ditugaskan
    public function done(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->status === 'in progress', 403);
        abort_unless((int) $ticket->assigned_to === (int) $request->user()->id, 403);

        $ticket->update([
            'status'      => 'done',
            'resolved_at' => now(),
        ]);

        return back();
    }

    // ==================== CATATAN PELACAKAN ====================

    public function storeTracking(Request $request, Ticket $ticket)
    {
        $this->abortIfDone($ticket);

        Tracking::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $request->user()->id,
            'note'      => $this->validatedNote($request),
        ]);

        return back();
    }

    public function updateTracking(Request $request, Ticket $ticket, Tracking $tracking)
    {
        $this->abortIfDone($ticket);
        $this->abortUnlessBelongsTo($ticket, $tracking);

        $tracking->update(['note' => $this->validatedNote($request)]);

        return back();
    }

    public function destroyTracking(Ticket $ticket, Tracking $tracking)
    {
        $this->abortIfDone($ticket);
        $this->abortUnlessBelongsTo($ticket, $tracking);

        $tracking->delete();

        return back()->with('success', 'Tracking berhasil dihapus');
    }

    // ==================== FUNGSI BANTU (hanya dipakai di controller ini) ====================

    // Tiket yang sudah selesai tidak boleh diubah catatan pelacakannya
    private function abortIfDone(Ticket $ticket): void
    {
        abort_if($ticket->status === 'done', 403);
    }

    // Catatan harus benar-benar milik tiket yang ada di alamat (URL)
    private function abortUnlessBelongsTo(Ticket $ticket, Tracking $tracking): void
    {
        abort_unless($tracking->ticket_id === $ticket->id, 404);
    }

    // Validasi isi catatan, mengembalikan teks catatannya
    private function validatedNote(Request $request): string
    {
        return $request->validate(['note' => 'required|string|max:500'])['note'];
    }
}
