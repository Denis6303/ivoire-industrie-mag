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
        Schema::create('article_industry_sector', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('industry_sector_id');
            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
            $table->foreign('industry_sector_id')->references('id')->on('industry_sectors')->cascadeOnDelete();
            $table->primary(['article_id', 'industry_sector_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_industry_sector');
    }
};
