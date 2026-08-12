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
            $table->boolean('is_settled')->default(false)->after('is_paid');
            $table->foreignId('settlement_of_id')->nullable()->after('is_settled')->constrained('sablon_employee_details')->nullOnDelete();
            $table->date('settled_at')->nullable()->after('settlement_of_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->dropForeign(['settlement_of_id']);
            $table->dropColumn(['is_settled', 'settlement_at', 'settlement_of_id']);
        });
    }
};
