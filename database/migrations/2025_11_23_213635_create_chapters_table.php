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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('summary')->nullable();
            $table->longText('body_markdown')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_sample')->default(false);
            $table->unique(['book_id', 'slug']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
