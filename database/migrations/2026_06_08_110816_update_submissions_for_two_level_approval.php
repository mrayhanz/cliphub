<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add new columns first
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('rejected_by')->nullable()->after('rejection_reason');
            $table->timestamp('brand_approved_at')->nullable()->after('rejected_by');
            $table->timestamp('admin_approved_at')->nullable()->after('brand_approved_at');
        });

        // Step 2: Modify the enum column using raw SQL to include both old and new values
        DB::statement("ALTER TABLE submissions MODIFY COLUMN status ENUM('pending_brand', 'approved_by_brand', 'rejected_by_brand', 'approved_by_admin', 'rejected_by_admin', 'pending', 'approved', 'rejected') DEFAULT 'pending_brand'");

        // Step 3: Update existing data
        DB::table('submissions')->where('status', 'pending')->update(['status' => 'pending_brand']);
        DB::table('submissions')->where('status', 'approved')->update(['status' => 'approved_by_admin']);
        DB::table('submissions')->where('status', 'rejected')->update(['status' => 'rejected_by_brand']);

        // Step 4: Remove old enum values
        DB::statement("ALTER TABLE submissions MODIFY COLUMN status ENUM('pending_brand', 'approved_by_brand', 'rejected_by_brand', 'approved_by_admin', 'rejected_by_admin') DEFAULT 'pending_brand'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data
        DB::table('submissions')->where('status', 'pending_brand')->update(['status' => 'pending']);
        DB::table('submissions')->where('status', 'approved_by_admin')->update(['status' => 'approved']);
        DB::table('submissions')->where('status', 'approved_by_brand')->update(['status' => 'pending']);
        DB::table('submissions')->whereIn('status', ['rejected_by_brand', 'rejected_by_admin'])->update(['status' => 'rejected']);

        // Revert enum
        DB::statement("ALTER TABLE submissions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");

        // Drop new columns
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['rejected_by', 'brand_approved_at', 'admin_approved_at']);
        });
    }
};
