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
        Schema::create('pivot_tables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('artwork_gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gallery_id')->constrained()->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('artwork_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('book_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_tag');
        Schema::dropIfExists('artwork_tag');
        Schema::dropIfExists('artwork_gallery');
        Schema::dropIfExists('pivot_tables');
    }
};
