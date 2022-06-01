<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $user = [
            [
                'name' => 'Super Admin',
                'nik' => '123456789',
                'username' => 'superadmin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('admin'),
                'role' => 'superadmin',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Admin',
                'nik' => '123456789',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
                'role' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Member',
                'nik' => '123456789',
                'username' => 'member',
                'email' => 'member@member.com',
                'password' => bcrypt('member'),
                'role' => 'member',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Master Stockist',
                'nik' => '123456789',
                'username' => 'stockist',
                'email' => 'stockist@stockist.com',
                'password' => bcrypt('stockist'),
                'role' => 'stockist',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Fadli Stockist',
                'nik' => '123456789',
                'username' => 'fadli',
                'email' => 'fadli@stockist.com',
                'password' => bcrypt('stockist'),
                'role' => 'stockist',
                'created_at' => Carbon::now(),
            ],
        ];

        User::insert($user);
    }
}
