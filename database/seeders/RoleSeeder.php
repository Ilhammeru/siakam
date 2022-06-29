<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();

        $role = [
            [
                'name' => 'superadmin',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'admin',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'tpu',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'dinas',
                'created_at' => Carbon::now()
            ],
        ];

        Role::insert($role);
    }
}
