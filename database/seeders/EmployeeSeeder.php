<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        Employee::create([
            'nik' => '1234567890',
            'name' => 'John Doe',
            'password' => Hash::make('password123'), // Pastikan password terenkripsi
        ]);

        Employee::create([
            'nik' => '0987654321',
            'name' => 'Jane Doe',
            'password' => Hash::make('password123'),
        ]);
    }
}
