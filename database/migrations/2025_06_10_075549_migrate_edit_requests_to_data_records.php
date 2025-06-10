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
        // First, migrate existing edit_requests to data_records
        $editRequests = DB::table('edit_requests')->get();
        
        foreach ($editRequests as $editRequest) {
            // Get the original data record
            $originalRecord = DB::table('data_records')->where('id', $editRequest->data_record_id)->first();
            
            if ($originalRecord) {
                // Create edit request record in data_records table
                $editRecordId = DB::table('data_records')->insertGetId([
                    'user_id' => $editRequest->user_id,
                    'is_edit_request' => true,
                    'parent_id' => $editRequest->data_record_id,
                    'status' => $editRequest->status,
                    'admin_id' => $editRequest->admin_id,
                    'admin_notes' => $editRequest->admin_notes,
                    'integer_field_1' => $originalRecord->integer_field_1,
                    'integer_field_2' => $originalRecord->integer_field_2,
                    'integer_field_3' => $originalRecord->integer_field_3,
                    'integer_field_4' => $originalRecord->integer_field_4,
                    'selector_field_1' => $originalRecord->selector_field_1,
                    'selector_field_2' => $originalRecord->selector_field_2,
                    'selector_field_3' => $originalRecord->selector_field_3,
                    'selector_field_4' => $originalRecord->selector_field_4,
                    'comment_field_1' => $originalRecord->comment_field_1,
                    'comment_field_2' => $originalRecord->comment_field_2,
                    'created_at' => $editRequest->created_at,
                    'updated_at' => $editRequest->updated_at,
                ]);

                // Create edit history entry
                DB::table('data_edit_history')->insert([
                    'data_record_id' => $editRecordId,
                    'field_name' => 'edit_request_migrated',
                    'old_value' => null,
                    'new_value' => 'Edit request migrated from old structure',
                    'changed_by' => $editRequest->admin_id,
                    'action_type' => 'edit_request',
                    'created_at' => $editRequest->created_at,
                    'updated_at' => $editRequest->updated_at,
                ]);
            }
        }

        // Drop the old edit_requests table
        Schema::dropIfExists('edit_requests');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate edit_requests table
        Schema::create('edit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('data_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
        });

        // Note: This down migration doesn't restore the original data
        // as it would be complex to reverse the migration logic
        // and may result in data loss
    }
};
