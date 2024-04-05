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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('name',24);
            $table->string('phone',10);
            $table->string('city_code',10);
            $table->string('district_code',10);
            $table->string('ward_code',10);
            $table->string('address');
            $table->string('note');
            $table->integer('total');
            $table->integer('delivery_fee');
            $table->tinyInteger('payment_type');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            // $table->foreign('discount_id')->references('id')->on('discounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
