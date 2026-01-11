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
        Schema::create('featured_items', function (Blueprint $table) {
            $table->id();
            $table->enum('item_type', ['artwork', 'book']);
            $table->unsignedBigInteger('item_id');
            $table->string('display_context')->default('home');
            $table->integer('display_order')->default(0);
            $table->index(['item_type', 'item_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_items');
    }
};
