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
            $table->date('late_eligible_at')->nullable()->after('settled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sablon_employee_details', function (Blueprint $table) {
            $table->dropColumn('late_eligible_at');
        });
    }
};
