<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            'Agung',
            'Mambar',
            'Ipin',
            'Empi',
            'Rizal',
            'Agus',
            'Harno',
            'RikoA',
            'Mujib',
            'Irwan',
            'Yoga',
            'Adi',
            'Viki',
            'Arip',
            'Ayet',
            'Hegler',
            'Dadang',
            'RikoB',
        ];

        foreach ($employees as $i => $employee) {
            Employee::create([
                'name' => $employee,
                'address' => 'tulungagung',
                'contact' => '0812345678' . $i,
            ]);
        }
    }
}
