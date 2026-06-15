<?php

namespace Database\Seeders;

use App\Models\Chama;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $chama = Chama::query()->firstOrFail();

        User::updateOrCreate(
            ['email' => 'treasurer@example.com'],
            [
                'name' => 'Treasurer One',
                'password' => Hash::make('password'),
                'role' => 'treasurer',
                'chama_id' => $chama->id,
                'email_verified_at' => now(),
            ]
        );

        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "member{$i}@example.com"],
                [
                    'name' => "Member {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'member',
                    'chama_id' => $chama->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
