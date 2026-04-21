<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MySQL : l’ENUM d’origine n’accepte pas de nouvelle valeur sans ALTER.
     * On passe en VARCHAR pour autoriser la position « sidebar_secondary ».
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE advertisements MODIFY COLUMN position VARCHAR(40) NOT NULL DEFAULT 'sidebar'");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE advertisements MODIFY COLUMN position ENUM('header', 'sidebar', 'in_article', 'footer') NOT NULL DEFAULT 'sidebar'");
    }
};
