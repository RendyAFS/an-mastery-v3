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
        Schema::create('fabric_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fabric_id')->nullable()->constrained('fabrics');
            $table->foreignId('color_fabric_id')->nullable()->constrained('color_fabrics');
            $table->integer('stock')->nullable();
            $table->longText('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabric_details');
    }
};
