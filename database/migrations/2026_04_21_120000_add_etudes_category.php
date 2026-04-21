<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('categories')->where('slug', 'etudes')->exists();
        if ($exists) {
            return;
        }

        $maxOrder = (int) DB::table('categories')
            ->whereNull('parent_id')
            ->max('order');

        DB::table('categories')->insert([
            'name' => 'Études',
            'slug' => 'etudes',
            'description' => 'Catégorie dédiée aux professeurs d’universités et aux experts.',
            'color' => '#7c3aed',
            'icon' => null,
            'parent_id' => null,
            'order' => $maxOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('categories')->where('slug', 'etudes')->delete();
    }
};
