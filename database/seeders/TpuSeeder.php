<?php

namespace Database\Seeders;

use App\Models\Tpu;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TpuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tpu::truncate();
        $tpu = [
            [
                'name' => 'TPU Kebun Jeruk',
                'address' => 'Jl. kebun jeruk kemang',
                'phone' => '085795327357',
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'TPU Makam Pahlawan',
                'address' => 'Jl. Pahlawan Tanpa Tanda Jasa',
                'phone' => '085795327357',
                'created_at' => Carbon::now()
            ]
        ];

        Tpu::insert($tpu);
    }
}
