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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->decimal('rent_price', 10, 2);
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('old_rent_price', 10, 2)->nullable();
            $table->decimal('old_purchase_price', 10, 2)->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('lot_area')->nullable();
            $table->integer('floor_area')->nullable();
            $table->date('year_built')->nullable();
            $table->boolean('is_water')->nullable();
            $table->integer('stories')->nullable();
            $table->boolean('is_new_roofing')->nullable();
            $table->integer('garage')->nullable();
            $table->integer('is_luggage')->nullable();
            $table->string('image_url')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->enum('status', ['available', 'sold', 'rented'])->default('available');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
