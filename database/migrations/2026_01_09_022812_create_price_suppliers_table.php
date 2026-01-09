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
        Schema::create('price_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('type_fabric_id')->nullable()->constrained('type_fabrics');
            $table->foreignId('type_color_id')->nullable()->constrained('type_colors');
            $table->integer('price')->nullable();
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
        Schema::dropIfExists('price_suppliers');
    }
};
