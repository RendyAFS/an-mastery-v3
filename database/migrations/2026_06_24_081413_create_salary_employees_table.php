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
        Schema::create('salary_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->integer('fee')->nullable();
            $table->json('additional_fee')->nullable();
            $table->string('status')->nullable();
            $table->date('date')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->userstamps();
            $table->softDeletes();
            $table->userstampSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_employees');
    }
};
