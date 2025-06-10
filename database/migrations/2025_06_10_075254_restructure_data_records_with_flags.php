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
        Schema::table('data_records', function (Blueprint $table) {
            // Add flags and fields to support unified data/edit request management
            $table->boolean('is_edit_request')->default(false)->after('user_id');
            $table->unsignedBigInteger('parent_id')->nullable()->after('is_edit_request');
            $table->enum('status', ['active', 'pending', 'completed', 'rejected'])->default('active')->after('parent_id');
            $table->unsignedBigInteger('admin_id')->nullable()->after('status');
            $table->text('admin_notes')->nullable()->after('admin_id');
            
            // Add foreign key constraints
            $table->foreign('parent_id')->references('id')->on('data_records')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index(['is_edit_request', 'status']);
            $table->index(['user_id', 'is_edit_request']);
            $table->index(['parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_records', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['admin_id']);
            $table->dropIndex(['is_edit_request', 'status']);
            $table->dropIndex(['user_id', 'is_edit_request']);
            $table->dropIndex(['parent_id']);
            $table->dropColumn(['is_edit_request', 'parent_id', 'status', 'admin_id', 'admin_notes']);
        });
    }
};
