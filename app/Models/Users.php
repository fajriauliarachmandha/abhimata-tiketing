<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Users extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'department_id',
        'role'
    ];

    // untuk menyembunyikan dari network tab
    protected $hidden = [
        'password',
        'remember_token'
    ];

    // melakukan hash pada password
    protected function casts(): array{
        return[
            'password'=>'hashed'
        ];
    }

    // relasi ke departemen - 1 department hanya untuk 1 pengguna
    public function department(){
        return $this->belongsTo(Department::class);
    }

    // ngecek role it support
    public function isItSupport():bool{
        return $this->role === 'it_support';
    }

    // ngecek role departement
    public function isDepartment():bool{
        return $this->role === 'department';
    }
}
