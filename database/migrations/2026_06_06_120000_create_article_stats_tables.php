<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_stats', function (Blueprint $table) {
            $table->foreignId('article_id')->primary()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('views_total')->default(0);
            $table->unsignedBigInteger('views_unique')->default(0);
            $table->unsignedBigInteger('views_returning')->default(0);
            $table->unsignedBigInteger('views_organic')->default(0);
            $table->unsignedBigInteger('views_social')->default(0);
            $table->unsignedBigInteger('views_direct')->default(0);
            $table->unsignedBigInteger('views_referral')->default(0);
            $table->unsignedBigInteger('views_campaign')->default(0);
            $table->unsignedBigInteger('views_homepage')->default(0);
            $table->unsignedBigInteger('views_category')->default(0);
            $table->unsignedBigInteger('views_internal')->default(0);
            $table->unsignedBigInteger('views_newsletter')->default(0);
            $table->unsignedBigInteger('views_fr')->default(0);
            $table->unsignedBigInteger('views_en')->default(0);
            $table->unsignedBigInteger('views_mobile')->default(0);
            $table->unsignedBigInteger('views_tablet')->default(0);
            $table->unsignedBigInteger('views_desktop')->default(0);
            $table->unsignedBigInteger('og_crawler_hits')->default(0);
            $table->unsignedBigInteger('bounces')->default(0);
            $table->unsignedBigInteger('shares_total')->default(0);
            $table->unsignedBigInteger('shares_facebook')->default(0);
            $table->unsignedBigInteger('shares_linkedin')->default(0);
            $table->unsignedBigInteger('shares_twitter')->default(0);
            $table->unsignedBigInteger('shares_whatsapp')->default(0);
            $table->unsignedBigInteger('shares_copy')->default(0);
            $table->unsignedBigInteger('scroll_25')->default(0);
            $table->unsignedBigInteger('scroll_50')->default(0);
            $table->unsignedBigInteger('scroll_75')->default(0);
            $table->unsignedBigInteger('scroll_100')->default(0);
            $table->unsignedBigInteger('qualified_reads')->default(0);
            $table->unsignedBigInteger('time_on_page_total_seconds')->default(0);
            $table->unsignedBigInteger('time_on_page_samples')->default(0);
            $table->unsignedBigInteger('clicks_internal_links')->default(0);
            $table->unsignedBigInteger('clicks_external_links')->default(0);
            $table->unsignedBigInteger('clicks_related')->default(0);
            $table->unsignedBigInteger('clicks_cover_image')->default(0);
            $table->unsignedBigInteger('clicks_secondary_image')->default(0);
            $table->unsignedBigInteger('clicks_newsletter')->default(0);
            $table->unsignedBigInteger('clicks_jobs')->default(0);
            $table->unsignedBigInteger('clicks_companies')->default(0);
            $table->unsignedBigInteger('newsletter_signups')->default(0);
            $table->unsignedInteger('peak_views_count')->default(0);
            $table->date('peak_views_date')->nullable();
            $table->timestamp('first_view_at')->nullable();
            $table->timestamp('last_view_at')->nullable();
            $table->timestamps();
        });

        Schema::create('article_stat_daily', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->unsignedInteger('shares')->default(0);
            $table->unsignedInteger('qualified_reads')->default(0);
            $table->unsignedInteger('time_on_page_total_seconds')->default(0);
            $table->unsignedInteger('time_on_page_samples')->default(0);
            $table->unsignedInteger('views_fr')->default(0);
            $table->unsignedInteger('views_en')->default(0);
            $table->unique(['article_id', 'date']);
            $table->index(['article_id', 'date']);
        });

        Schema::create('article_view_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->uuid('visitor_key');
            $table->unsignedInteger('view_count')->default(1);
            $table->unsignedTinyInteger('max_scroll_depth')->default(0);
            $table->unsignedInteger('total_time_seconds')->default(0);
            $table->timestamp('first_viewed_at');
            $table->timestamp('last_viewed_at');
            $table->string('referrer', 500)->nullable();
            $table->string('source_type', 32)->nullable();
            $table->string('utm_source', 120)->nullable();
            $table->string('utm_medium', 120)->nullable();
            $table->string('utm_campaign', 120)->nullable();
            $table->string('locale', 5)->nullable();
            $table->string('device_type', 16)->nullable();
            $table->string('browser', 64)->nullable();
            $table->string('os', 64)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->boolean('is_qualified')->default(false);
            $table->unique(['article_id', 'visitor_key']);
            $table->index(['article_id', 'last_viewed_at']);
        });

        Schema::create('article_stat_referrers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('referrer_host', 255);
            $table->unsignedBigInteger('hit_count')->default(0);
            $table->unique(['article_id', 'referrer_host']);
        });

        Schema::create('article_stat_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->uuid('visitor_key')->nullable();
            $table->string('event_type', 64);
            $table->json('payload')->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('source_type', 32)->nullable();
            $table->string('utm_source', 120)->nullable();
            $table->string('utm_medium', 120)->nullable();
            $table->string('utm_campaign', 120)->nullable();
            $table->string('locale', 5)->nullable();
            $table->string('device_type', 16)->nullable();
            $table->string('browser', 64)->nullable();
            $table->string('os', 64)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['article_id', 'event_type']);
            $table->index(['article_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_stat_events');
        Schema::dropIfExists('article_stat_referrers');
        Schema::dropIfExists('article_view_sessions');
        Schema::dropIfExists('article_stat_daily');
        Schema::dropIfExists('article_stats');
    }
};
