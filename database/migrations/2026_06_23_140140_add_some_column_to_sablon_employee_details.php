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
        Schema::table('type_colors', function (Blueprint $table) {
            $table->integer('name')->change();
        });
        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->integer('layers')->nullable()->after('employee_id');
            $table->foreignId('employee_change_id')->nullable()->after('is_change')->constrained('employees')->nullOnDelete();
            $table->boolean('is_payed')->nullable()->after('employee_change_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('type_colors', function (Blueprint $table) {
            $table->string('name')->change();
        });
        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->dropForeign(['employee_change_id']);
            $table->dropColumn(['employee_change_id', 'is_payed', 'layers']);
        });
    }
};
