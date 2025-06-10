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
        Schema::create('data_edit_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_record_id');
            $table->string('field_name', 50);
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->unsignedBigInteger('changed_by');
            $table->enum('action_type', ['create', 'update', 'edit_request', 'edit_complete']);
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('data_record_id')->references('id')->on('data_records')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['data_record_id', 'created_at']);
            $table->index(['changed_by']);
            $table->index(['action_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_edit_history');
    }
};
