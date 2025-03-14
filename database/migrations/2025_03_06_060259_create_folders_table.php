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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); 
            $table->string('file_id')->nullable(); 
            $table->string('folder_id')->nullable(); 
            $table->string('name'); 
            $table->text('path')->nullable(); 
            $table->enum('type', ['folder', 'file']); 
            $table->boolean('is_hidden')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
