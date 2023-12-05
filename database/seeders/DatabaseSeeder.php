<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => 1001,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'age' => 30
        ]);

        User::factory()->create([
            'id' => 1002,
            'name' => 'Test2 User',
            'email' => 'test2@example.com',
            'age' => 30
        ]);

        Account::factory()->create([
            'id' => 101,
            'user_id' => 1001,
            'balance' => 10.00
        ]);

        Account::factory()->create([
            'id' => 102,
            'user_id' => 1002,
            'balance' => 10.00
        ]);
    }
}
