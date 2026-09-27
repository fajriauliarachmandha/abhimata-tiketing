<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\Users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Department::create([
            'department_code' => 'IT',
            'department_name' => 'Support',
        ]);
        Users::create([
            'name'=>'Pajrid Aulia Rachmandha',
            'username' => 'admin',
            'password' => 'admin',
            'department_id' => 1,
            'role' => 'it_support',
        ]);
    }
}
