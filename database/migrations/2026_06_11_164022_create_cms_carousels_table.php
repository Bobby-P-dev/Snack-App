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
        Schema::create('cms_carousels', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_url');
            $table->text('description')->nullable();
            $table->string('link_url')->nullable(); // CTA link
            $table->integer('order')->default(0); // untuk sorting
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_carousels');
    }
};
