<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = \App\Models\User::factory()->create([
            'name' => 'Raihan Makarim',
            'email' => 'macxknight@gmail.com',
        ]);
        $userAdmin->assignRole('Administrator');

        $user = \App\Models\User::factory()
        ->count(50)
        ->create();
        $user->assignRole('users');

    }
}
