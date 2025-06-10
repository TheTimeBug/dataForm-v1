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
        Schema::create('data_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 4 integer fields
            $table->integer('integer_field_1');
            $table->integer('integer_field_2');
            $table->integer('integer_field_3');
            $table->integer('integer_field_4');
            
            // 4 selector fields
            $table->string('selector_field_1');
            $table->string('selector_field_2');
            $table->string('selector_field_3');
            $table->string('selector_field_4');
            
            // 2 comment fields
            $table->text('comment_field_1');
            $table->text('comment_field_2');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_records');
    }
};
