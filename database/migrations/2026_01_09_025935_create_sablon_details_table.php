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
        Schema::create('sablon_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sablon_id')->nullable()->constrained('sablons');
            $table->foreignId('color_fabric_id')->nullable()->constrained('color_fabrics');
            $table->integer('long_fabric')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sablon_details');
    }
};
