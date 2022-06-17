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
                'grave_block' => 'kenanga',
                'quota' => 123,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'mawar',
                'quota' => 55,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 1,
                'grave_block' => 'melati',
                'quota' => 80,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'kamboja',
                'quota' => 2,
                'created_at' => Carbon::now()
            ],
            [
                'tpu_id' => 2,
                'grave_block' => 'melati',
                'quota' => 5,
                'created_at' => Carbon::now()
            ],
        ];
        TpuGrave::insert($data);
    }
}
