<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'rating', 'tags'];
    protected $casts = ['tags' => 'array'];

    public function ticket() { return $this->belongsTo(Ticket::class); }
    public function user()   { return $this->belongsTo(User::class); }
}
