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
        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->dropForeign(['fabric_detail_id']);
            $table->dropColumn('fabric_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->foreignId('color_fabric_id')->nullable()->after('sablon_id')->constrained('color_fabrics');
        });
    }
};
