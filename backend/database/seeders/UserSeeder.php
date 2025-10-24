<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        // 👤 Usuário comum 1
        User::create([
            'name' => 'Usuário 1',
            'email' => 'anderson@unsonst.dev',
            'password' => Hash::make('123456'),

        ]);

    }
}
