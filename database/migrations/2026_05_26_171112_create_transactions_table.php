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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'deposit', 'escrow_hold', 'escrow_release', 'escrow_refund', 'payout'
            $table->bigInteger('amount'); // dalam rupiah, positif = masuk, negatif = keluar
            $table->string('description')->nullable();
            $table->string('reference_type')->nullable(); // 'campaign', 'deposit', 'withdrawal'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->bigInteger('balance_after')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
