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
        Schema::create('related_articles', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('related_id');
            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
            $table->foreign('related_id')->references('id')->on('articles')->cascadeOnDelete();
            $table->primary(['article_id', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related_articles');
    }
};
