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
        Schema::create('details_book_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('book_movement_id');
            $table->string('type_movement');
            $table->integer('book_id');
            $table->float('quantity',100,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_book_movements');
    }
};
