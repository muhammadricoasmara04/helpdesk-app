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
        Schema::create('application_problems', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('application_id');
            $table->uuid('ticket_priority_id');
            $table->string('problem_name');
            $table->text('description');
            $table->uuid('created_id');
            $table->uuid('updated_id');
            $table->timestamps();

            $table
                ->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade');
            $table
                ->foreign('ticket_priority_id')
                ->references('id')
                ->on('ticket_priority')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_problems');
    }
};
