<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->bigInteger('escrow_amount')->default(0)->after('budget_spent');
            $table->bigInteger('escrow_paid')->default(0)->after('escrow_amount');
            $table->bigInteger('escrow_refunded')->default(0)->after('escrow_paid');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['escrow_amount', 'escrow_paid', 'escrow_refunded']);
        });
    }
};
