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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('users');
            $table->integer('lose_score');
            $table->string('password');
            $table->integer('number_of_players');
            $table->integer('number_of_shuffles');
            $table->integer('multiple_score')->default('0');
            $table->boolean('is_finished')->default('0');
            $table->integer('round')->default('0');
            $table->integer('turns')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
