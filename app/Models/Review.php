<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Kolom yang boleh diisi lewat Review::create([...])
    protected $fillable = ['ticket_id', 'user_id', 'rating', 'tags'];

    // `tags` disimpan sebagai JSON di database, tapi dipakai sebagai array di PHP/Vue
    protected $casts = ['tags' => 'array'];

    // Tiket yang dinilai
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // User yang memberi penilaian
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
