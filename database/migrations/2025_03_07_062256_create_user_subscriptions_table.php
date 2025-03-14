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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->string('membership_id');
            $table->string('transaction_id')->unique()->nullable();
            $table->text('payment_image')->nullable();
            $table->string('title');
            $table->float('amount',15,2)->default(0);
            $table->string('validity')->nullable();
            $table->string('remark')->nullable();
            $table->enum('status',['pending','Rejected','Accepted'])->default('pending'); 
            $table->boolean('is_active',[0,1])->default(0);
            $table->timestamp('admin_approved_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
