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
        Schema::create('commission_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_message_id')->constrained()->cascadeOnDelete();
            $table->longText('project_description')->nullable();
            $table->string('budget_range')->nullable();
            $table->date('desired_due_date')->nullable();
            $table->enum('status', ['new', 'quoted', 'accepted', 'rejected', 'completed'])->default('new')->index();
            $table->decimal('quote_amount', 10, 2)->nullable();
            $table->string('invoice_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_requests');
    }
};
