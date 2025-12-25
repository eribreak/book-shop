<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            DistrictSeeder::class,
            WardSeeder::class,

            CategorySeeder::class,
            PublisherSeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
            ImageSeeder::class,

            EmployeeSeeder::class,
            OrderSeeder::class,
            OrderDetailSeeder::class,

            CartSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
        ]);
    }
}
