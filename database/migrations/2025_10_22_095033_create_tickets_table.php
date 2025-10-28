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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_status_id')->nullable();
            $table->uuid('ticket_priority_id')->nullable();
            $table->uuid('application_id')->nullable();
            $table->uuid('application_problem_id')->nullable();
            $table->string('ticket_code');
            $table->string('employee_number');
            $table->string('employee_name');
            $table->text('position_name')->nullable();
            $table->text('organization_name')->nullable();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->timestamps();




            $table->foreign('ticket_status_id')->references('id')->on('ticket_status')->onDelete('cascade');
            $table->foreign('ticket_priority_id')->references('id')->on('ticket_priority')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('application_problem_id')->references('id')->on('application_problems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
