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
        Schema::create('cms_social_medias', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // instagram, whatsapp, facebook, tiktok, twitter, etc
            $table->string('url');
            $table->string('icon_name')->nullable(); // untuk icon display
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_social_medias');
    }
};
