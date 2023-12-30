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
        Schema::create('book_movements', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type_movement');
            $table->string('status');
            $table->date('loan_date')->nullable();
            $table->date('return_date')->nullable();
            $table->date('real_date')->nullable();
            $table->integer('user_id');
            $table->integer('customer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_movements');
    }
};
