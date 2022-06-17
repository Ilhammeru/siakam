<?php

namespace Database\Seeders;

use App\Models\BurialType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BurialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BurialType::truncate();
        $data = [
            ['name' => 'Biasa'],
            ['name' => 'Covid'],
            ['name' => 'Mr. X'],
            ['name' => 'WNA'],
            ['name' => 'Masyarakat Tidak Mampu']
        ];

        BurialType::insert($data);
    }
}
