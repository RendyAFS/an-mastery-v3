<?php

namespace Database\Seeders;

use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkshopDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure default workshop (id = 1) exists
        $workshop = Workshop::withTrashed()->find(1);
        if (!$workshop) {
            $workshop = Workshop::create([
                'id'        => 1,
                'name'      => 'Andri Sablon Gedangsewu',
                'location'  => 'Gedangsewu',
                'is_active' => true,
            ]);
        } else {
            if ($workshop->trashed()) {
                $workshop->restore();
            }
            $workshop->update([
                'name'      => 'Andri Sablon Gedangsewu',
                'is_active' => true,
            ]);
        }

        $defaultWorkshopId = $workshop->id;

        // 2. Assign default workshop to users without a workshop
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'workshop_id')) {
            DB::table('users')
                ->whereNull('workshop_id')
                ->update(['workshop_id' => $defaultWorkshopId]);
        }

        // 3. Operational tables to populate
        $tables = [
            'suppliers',
            'employees',
            'image_fabrics',
            'color_fabrics',
            'type_fabrics',
            'type_colors',
            'price_suppliers',
            'price_employees',
            'presences',
            'fabrics',
            'sablons',
            'bill_suppliers',
            'salary_employees',
            'memos',
            'galleries',
            'bonuses',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'workshop_id')) {
                DB::table($tableName)
                    ->whereNull('workshop_id')
                    ->update(['workshop_id' => $defaultWorkshopId]);
            }
        }
    }
}
