<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public const TAGS = ['Cepat', 'Ramah', 'Solusi tepat', 'Komunikatif', 'Lambat', 'Kurang jelas'];

    public function store(Request $request)
    {
        $data = $request->validate([
            'ticket_id' => ['required', 'exists:tickets,id'],
            'rating'    => ['required', 'integer', 'between:1,5'],
            'tags'      => ['nullable', 'array'],
            'tags.*'    => ['string', Rule::in(self::TAGS)],
        ], [
            'rating.between' => 'Pilih rating 1 sampai 5 bintang.',
        ]);

        $ticket = Ticket::findOrFail($data['ticket_id']);

        // Hanya pengaju tiket yang boleh menilai (sesuaikan kolomnya dengan tabel tickets kamu)
        abort_unless((int) $ticket->created_by === (int) $request->user()->id, 403);

        if ($ticket->status !== 'done') {
            return back()->withErrors(['rating' => 'Tiket belum selesai, belum bisa dinilai.']);
        }

        if ($ticket->review()->exists()) {
            return back()->withErrors(['rating' => 'Tiket ini sudah dinilai.']);
        }

        Review::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $request->user()->id,
            'rating'    => $data['rating'],
            'tags'      => $data['tags'] ?? [],
        ]);

        return back()->with('success', 'Terima kasih atas penilaiannya!');
    }
}
