<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Order;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::query()->get();
        if ($employees->isEmpty()) {
            $employees = Employee::factory()->count(10)->create();
        }

        $wards = Ward::query()->with('district.province')->get();
        if ($wards->isEmpty()) {
            $wards = Ward::factory()->count(20)->create();
            $wards->load('district.province');
        }

        $users = User::factory()->count(25)->create()->each(function (User $user) use ($wards) {
            $ward = $wards->random();
            $user->forceFill([
                'ward_id' => $ward->id,
                'district_id' => $ward->district_id,
                'province_id' => $ward->district?->province_id,
            ])->save();
        });

        foreach (range(1, 40) as $i) {
            $user = $users->random();
            $employee = $employees->random();

            Order::factory()->create([
                'user_id' => $user->id,
                'employee_code' => $employee->employee_code,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'address' => $user->address,
                'province_id' => $user->province_id,
                'district_id' => $user->district_id,
                'ward_id' => $user->ward_id,
            ]);
        }
    }
}
