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
        Schema::table('fabrics', function (Blueprint $table) {
            $table->foreignId('type_fabric_id')->nullable()->after('supplier_id')->constrained('type_fabrics')->nullOnDelete();
            $table->date('date_coming')->nullable()->after('type_fabric_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fabrics', function (Blueprint $table) {
            $table->dropForeign(['type_fabric_id']);
            $table->dropColumn(['type_fabric_id', 'date_coming']);
        });
    }
};
