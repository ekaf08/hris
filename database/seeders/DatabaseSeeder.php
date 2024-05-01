<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        /** pemanggilan seeder*/
        $this->call([
            UserSeeder::class,
        ]);
        /** End -*- pemanggilan seeder*/

        /** Membuat data Dummy dari factorty */
        \App\Models\User::factory(79)->create();
        \App\Models\Company::factory(20)->create();
        \App\Models\Team::factory(20)->create();
        \App\Models\Role::factory(20)->create();
        \App\Models\Responsibility::factory(200)->create();
        \App\Models\Employee::factory(200)->create();
        /** END Membuat data Dummy dari factorty */
    }
}
