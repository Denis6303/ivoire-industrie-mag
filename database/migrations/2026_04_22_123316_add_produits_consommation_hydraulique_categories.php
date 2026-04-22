<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $industrie = DB::table('categories')->where('slug', 'industrie')->first();

        if (! $industrie) {
            return;
        }

        $lastOrder = DB::table('categories')
            ->where('parent_id', $industrie->id)
            ->max('order') ?? 0;

        $toInsert = [
            ['name' => 'Produits de consommation', 'slug' => 'produits-de-consommation', 'color' => '#0ea5e9'],
            ['name' => 'Hydraulique',               'slug' => 'hydraulique',               'color' => '#0284c7'],
        ];

        foreach ($toInsert as $cat) {
            $exists = DB::table('categories')->where('slug', $cat['slug'])->exists();
            if ($exists) {
                continue;
            }

            $lastOrder++;
            DB::table('categories')->insert([
                'name'       => $cat['name'],
                'slug'       => $cat['slug'],
                'parent_id'  => $industrie->id,
                'order'      => $lastOrder,
                'color'      => $cat['color'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('categories')
            ->whereIn('slug', ['produits-de-consommation', 'hydraulique'])
            ->delete();
    }
};
