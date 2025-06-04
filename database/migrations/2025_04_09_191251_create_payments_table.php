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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'paypal']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('environment')->default('test');
            $table->string('stripe_payment_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
