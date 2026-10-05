<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * List of operational tables that belong to a workshop.
     */
    protected array $tables = [
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

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'workshop_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('workshop_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('workshops')
                        ->cascadeOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'workshop_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('workshop_id');
                });
            }
        }
    }
};
