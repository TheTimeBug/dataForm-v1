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
        // Update admin_type enum to include superadmin
        DB::statement("ALTER TABLE users MODIFY COLUMN admin_type ENUM('national', 'divisional', 'district', 'upazila', 'superadmin') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove superadmin admin_type and revert to original enum
        DB::statement("UPDATE users SET admin_type = 'national' WHERE admin_type = 'superadmin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN admin_type ENUM('national', 'divisional', 'district', 'upazila') NULL");
    }
}; 