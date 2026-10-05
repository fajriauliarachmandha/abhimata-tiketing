<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tracking extends Model
{
    protected $fillable = ['ticket_id','user_id','note'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
