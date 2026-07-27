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
            $table->foreignId('salary_employee_id')
                ->nullable()
                ->after('employee_id')
                ->constrained('salary_employees')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->dropForeign(['salary_employee_id']);
            $table->dropColumn('salary_employee_id');
        });
    }
};
