<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'department_id',
        'created_by',
        'assigned_to',
        'category',
        'priority',
        'description',
        'photo',
        'status',
        'eta',
    ];

    protected $casts = [
        'eta' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
