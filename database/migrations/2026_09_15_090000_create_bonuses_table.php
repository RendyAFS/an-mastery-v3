<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->integer('min')->default(0);
            $table->integer('bonus')->default(0);
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->userstamps();
            $table->softDeletes();
            $table->userstampSoftDeletes();
        });

        $now = now();
        DB::table('bonuses')->insert([
            [
                'min'        => 500000,
                'bonus'      => 25000,
                'notes'      => 'Minimal Rp 500.000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'min'        => 400000,
                'bonus'      => 20000,
                'notes'      => 'Minimal Rp 400.000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'min'        => 250000,
                'bonus'      => 15000,
                'notes'      => 'Minimal Rp 250.000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'min'        => 200000,
                'bonus'      => 10000,
                'notes'      => 'Minimal Rp 200.000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
