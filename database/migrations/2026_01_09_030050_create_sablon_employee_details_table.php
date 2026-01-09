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
        Schema::create('sablon_employee_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sablon_id')->nullable()->constrained('sablons');
            $table->foreignId('fabric_detail_id')->nullable()->constrained('fabric_details');
            $table->foreignId('employee_id')->nullable()->constrained('employees');
            $table->integer('fee')->nullable();
            $table->json('additional_fee')->nullable();
            $table->integer('total')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sablon_employee_details');
    }
};
