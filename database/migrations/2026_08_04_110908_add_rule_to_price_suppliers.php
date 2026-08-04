<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('price_suppliers', function (Blueprint $table) {
            $table->unique(
                ['supplier_id', 'type_fabric_id', 'type_color_id'],
                'price_suppliers_supplier_fabric_color_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_suppliers', function (Blueprint $table) {
            $table->dropUnique('price_suppliers_supplier_fabric_color_unique');
        });
    }
};
