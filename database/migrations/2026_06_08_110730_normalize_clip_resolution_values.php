<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('clips')->where('resolution', '480p')->update(['resolution' => '480']);
        DB::table('clips')->where('resolution', '720p')->update(['resolution' => '720']);
        DB::table('clips')->where('resolution', '1080p')->update(['resolution' => '1080']);
        DB::table('clips')->where('resolution', '1440p')->update(['resolution' => '1080']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('clips')->where('resolution', '480')->update(['resolution' => '480p']);
        DB::table('clips')->where('resolution', '720')->update(['resolution' => '720p']);
        DB::table('clips')->where('resolution', '1080')->update(['resolution' => '1080p']);
    }
};
