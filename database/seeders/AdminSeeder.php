<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'bellamonte@resort.com'],
            [
                'name'     => 'BM Resort',
                'password' => Hash::make('bmresort001'),
                'role'     => 'admin', 
                'status'   => 'active',   
            ]
        );
    }
}
