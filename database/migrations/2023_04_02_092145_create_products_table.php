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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->unsignedBigInteger('category_id');
            $table->string('thumb', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('slug', 255)->unique();
            $table->tinyInteger('status');
            $table->integer('weight');
            $table->integer('height')->default(0);
            $table->integer('length')->default(0);
            $table->integer('width')->default(0);
            $table->string('note');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
