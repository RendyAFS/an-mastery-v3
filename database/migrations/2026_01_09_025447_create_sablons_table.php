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
        Schema::create('sablons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('fabric_id')->nullable()->constrained('fabrics');
            $table->foreignId('image_fabric_id')->nullable()->constrained('image_fabrics');
            $table->foreignId('type_color_id')->nullable()->constrained('type_colors');
            $table->foreignId('type_fabric_id')->nullable()->constrained('type_fabrics');
            $table->foreignId('price_employee_id')->nullable()->constrained('price_employees');
            $table->integer('total_long_fabric')->nullable();
            $table->integer('total_sablon')->nullable()->comment('total long fabric * price employee');
            $table->date('date_sablon')->nullable();
            $table->string('status')->nullable()->comment('enum StatusSablonEnum');
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
        Schema::dropIfExists('sablons');
    }
};
