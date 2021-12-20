<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(['name' => 'Hasun Nilupul', 'email' => 'hasunnilupul21@gmail.com', 'password' => '1234', 'role_id'=>1, 'created_at'=>now()]);
        DB::table('users')->insert(['name' => 'Softwire Solutions', 'email' => 'softwiresolution@gmail.com', 'password' => '12345678', 'role_id'=>2, 'created_at'=>now()]);
    }
}
