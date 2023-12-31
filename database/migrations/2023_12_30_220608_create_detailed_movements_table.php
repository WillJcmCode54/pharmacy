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
        Schema::create('detailed_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('movement_id');
            $table->string('type_movement');
            $table->integer('medicine_id');
            $table->double('amount',100,2);
            $table->double('quantity',100,2);
            $table->double('subtotal',100,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailed_movements');
    }
};
