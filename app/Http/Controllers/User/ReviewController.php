<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    // Daftar tag yang boleh dipilih (harus sama dengan `reviewTags` di halaman Vue detail tiket)
    public const TAGS = ['Cepat', 'Ramah', 'Solusi tepat', 'Komunikatif', 'Lambat', 'Kurang jelas'];

    // Simpan penilaian untuk tiket yang sudah selesai
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => ['required', 'exists:tickets,id'],
            'rating'    => ['required', 'integer', 'between:1,5'],
            'tags'      => ['nullable', 'array'],
            'tags.*'    => ['string', Rule::in(self::TAGS)],
        ], [
            'rating.between' => 'Pilih rating 1 sampai 5 bintang.',
        ]);

        $user   = $request->user();
        $ticket = Ticket::findOrFail($validated['ticket_id']);

        // 1. Hanya pembuat tiket yang boleh menilai
        abort_unless((int) $ticket->created_by === (int) $user->id, 403);

        // 2. Tiket harus sudah selesai
        if ($ticket->status !== 'done') {
            return back()->withErrors(['rating' => 'Tiket belum selesai, belum bisa dinilai.']);
        }

        // 3. Satu tiket hanya boleh dinilai satu kali
        if ($ticket->review()->exists()) {
            return back()->withErrors(['rating' => 'Tiket ini sudah dinilai.']);
        }

        Review::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'rating'    => $validated['rating'],
            'tags'      => $validated['tags'] ?? [],
        ]);

        return back()->with('success', 'Terima kasih atas penilaiannya!');
    }
}
