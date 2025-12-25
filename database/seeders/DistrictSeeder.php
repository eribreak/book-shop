<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = Province::query()->get();

        if ($provinces->isEmpty()) {
            $provinces = Province::factory()->count(10)->create();
        }

        foreach ($provinces as $province) {
            District::factory()->count(5)->create([
                'province_id' => $province->id,
            ]);
        }
    }
}
