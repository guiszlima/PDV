<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'), // Senha já criptografada
            'remember_token' => Str::random(10),
            'created_at' => '2025-01-13 00:54:59',
            'updated_at' => '2025-01-13 02:13:14',
            'is_active' => 1, // Assume que esse é o campo para status
            'is_admin' => 1, // Assume que esse é o campo para indicar se o usuário é admin
        
        ]);
    }
}