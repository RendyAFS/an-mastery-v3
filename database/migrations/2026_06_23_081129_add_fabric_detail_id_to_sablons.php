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
        Schema::table('sablons', function (Blueprint $table) {
         $table->foreignId('fabric_detail_id')->nullable()->after('fabric_id')->constrained('fabric_details')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sablons', function (Blueprint $table) {
            $table->dropForeign(['fabric_detail_id']);
            $table->dropColumn('fabric_detail_id');
        });
    }
};
