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
        Schema::table('artworks', function (Blueprint $table) {
            $table->string('image_status')->default('pending')->after('metadata');
            $table->text('image_error')->nullable()->after('image_status');
            $table->timestamp('image_processed_at')->nullable()->after('image_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['image_status', 'image_error', 'image_processed_at']);
        });
    }
};
