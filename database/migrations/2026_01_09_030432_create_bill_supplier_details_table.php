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
        Schema::create('bill_supplier_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_supplier_id')->nullable()->constrained('bill_suppliers');
            $table->foreignId('sablon_detail_id')->nullable()->constrained('sablon_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_supplier_details');
    }
};
