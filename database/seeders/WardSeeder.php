<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = District::query()->get();

        if ($districts->isEmpty()) {
            $districts = District::factory()->count(30)->create();
        }

        foreach ($districts as $district) {
            Ward::factory()->count(5)->create([
                'district_id' => $district->id,
            ]);
        }
    }
}
