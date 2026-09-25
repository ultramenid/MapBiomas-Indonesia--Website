<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->default('internal'); // internal | external
            $table->string('title_id');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->text('excerpt_id')->nullable();
            $table->text('excerpt_en')->nullable();
            $table->string('image_path')->nullable();
            $table->string('external_url')->nullable(); // external news only
            $table->longText('content_id')->nullable(); // internal news only
            $table->longText('content_en')->nullable();
            $table->date('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
