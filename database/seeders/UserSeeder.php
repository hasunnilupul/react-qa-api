<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Rishan Darshana',
            'email' => 'rishandarshana@gmail.com',
            'password' => bcrypt(12345678),
            'role_id' => 1
        ]);
        User::create([
            'name' => 'Softwire Solutions',
            'email' => 'softwiresolution@gmail.com',
            'password' => bcrypt(12345678),
            'role_id' => 2
        ]);
    }
}
