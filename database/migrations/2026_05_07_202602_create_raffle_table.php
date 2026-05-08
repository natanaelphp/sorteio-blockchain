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
        Schema::create('raffle', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('participants');
            $table->enum('status', ['waiting', 'done']);
            $table->string('block_hash')->nullable();
            $table->string('winner')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffle');
    }
};
