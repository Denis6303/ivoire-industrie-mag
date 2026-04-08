<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $uploaderId = User::query()->where('email', 'admin@ivoireindustriemag.ci')->value('id');

        $items = [
            [
                'original_name' => 'cover-industry.jpg',
                'filename' => 'cover-industry.jpg',
                'path' => 'external/cover-industry.jpg',
                'url' => 'https://images.unsplash.com/photo-1581091870627-3d5a8c1e0c4f?auto=format&fit=crop&w=1400&q=80',
                'mime_type' => 'image/jpeg',
                'type' => 'image',
                'width' => 1400,
                'height' => 933,
                'alt' => 'Usine et chaîne de production',
                'caption' => 'Image de démonstration (Unsplash).',
            ],
            [
                'original_name' => 'cover-logistics.jpg',
                'filename' => 'cover-logistics.jpg',
                'path' => 'external/cover-logistics.jpg',
                'url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1400&q=80',
                'mime_type' => 'image/jpeg',
                'type' => 'image',
                'width' => 1400,
                'height' => 934,
                'alt' => 'Logistique et conteneurs',
                'caption' => 'Image de démonstration (Unsplash).',
            ],
            [
                'original_name' => 'banner-energy.jpg',
                'filename' => 'banner-energy.jpg',
                'path' => 'external/banner-energy.jpg',
                'url' => 'https://images.unsplash.com/photo-1509395176047-4a66953fd231?auto=format&fit=crop&w=1400&q=80',
                'mime_type' => 'image/jpeg',
                'type' => 'image',
                'width' => 1400,
                'height' => 933,
                'alt' => 'Énergie solaire',
                'caption' => 'Bannière de démonstration (Unsplash).',
            ],
        ];

        foreach ($items as $m) {
            Media::updateOrCreate(
                ['path' => $m['path']],
                array_merge($m, [
                    'disk' => 'public',
                    'size' => null,
                    'uploaded_by' => $uploaderId,
                ])
            );
        }
    }
}

