<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'ekaf',
                'email' => 'eka@email.com',
                'password' => Hash::make('password'),
            ],
        ];

        /** cek ketika seeder sudah di buat atau belum */
        foreach ($users as $user) {
            $user['created_at'] = now();
            $user['updated_at'] = null;
            if (!User::where('email', $user['email'])->first()) {
                User::create(
                    $user,
                );
            }
        }
    }
}
