<?php

namespace Database\Seeders;

use App\Models\TpuGrave;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TpuGraveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TpuGrave::truncate();
        $data = [
            [
                'tpu_id' => 1,
                'grave_block' => 'B3',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'B1',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'B2',
                'is_available' => FALSE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'A2',
                'is_available' => FALSE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'A3',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'A4',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'A5',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'A6',
                'is_available' => FALSE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'A7',
                'is_available' => FALSE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'A1',
                'is_available' => FALSE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'A2',
                'is_available' => FALSE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'A3',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'A4',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'B1',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'B2',
                'is_available' => TRUE,
                'created_at' => Carbon::now()
            ],
        ];
        TpuGrave::insert($data);
    }
}
