<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            'Sunar',
            'Yadi',
            'Bibit',
            'Santoso',
            'Mail',
            'Yanti',
            'Rusda',
            'Ahmad',
            'Sirajudin',
            'Udin',
        ];

        foreach ($suppliers as $i => $supplier) {
            Supplier::create([
                'name' => $supplier,
                'address' => 'tulungagung',
                'contact' => '0812345678' . $i,
            ]);
        }
    }
}
