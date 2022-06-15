<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

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
                'username' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('superadmin'),
                'role' => 'superadmin',
                'tpu_id' => 0,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'tpu_id' => 0,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'TPU 1',
                'username' => 'tpu',
                'email' => 'tpu@gmail.com',
                'password' => Hash::make('tpu'),
                'role' => 'tpu',
                'tpu_id' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'TPU 2',
                'username' => 'tpu2',
                'email' => 'tpu2@gmail.com',
                'password' => Hash::make('tpu2'),
                'role' => 'tpu',
                'tpu_id' => 2,
                'created_at' => Carbon::now(),
            ]
        ];

        User::insert($user);
    }
}
