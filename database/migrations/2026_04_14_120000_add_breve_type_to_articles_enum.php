<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE articles
            MODIFY type ENUM('news', 'analysis', 'interview', 'report', 'press_release', 'opinion', 'data', 'breve')
            NOT NULL DEFAULT 'news'
        ");
    }

    public function down(): void
    {
        DB::table('articles')->where('type', 'breve')->update(['type' => 'news']);

        DB::statement("
            ALTER TABLE articles
            MODIFY type ENUM('news', 'analysis', 'interview', 'report', 'press_release', 'opinion', 'data')
            NOT NULL DEFAULT 'news'
        ");
    }
};
