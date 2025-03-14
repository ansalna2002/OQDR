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
        Schema::create('reward_histories', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); 
            $table->integer('watch_time'); 
            $table->integer('points_earned'); 
            $table->timestamp('points_earn_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_histories');
    }
};
