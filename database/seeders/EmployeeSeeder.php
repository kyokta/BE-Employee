<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'image' => null,
            'name' => 'Employee 1',
            'phone' => '6281234567890',
            'divisi' => 1,
            'position' => 'Staff'
        ]);
    }
}