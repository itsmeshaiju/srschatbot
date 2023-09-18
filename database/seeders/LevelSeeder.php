<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 0; $i <= 10; $i++) {
            $data[] = [
                'name' => 'Level ' . $i,
            ];
        }
       
        Level::insert($data);
    }
}
