<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class SocialImageService
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    public const JPEG_QUALITY = 82;

    public function publicUrl(string $filename): string
    {
        return url('/og-image/'.$filename);
    }

    public function resolveCachedPath(string $filename): ?string
    {
        if (! $this->isAllowedFilename($filename)) {
            return null;
        }

        $source = storage_path('app/public/media/'.$filename);
        if (! is_file($source)) {
            return null;
        }

        $cacheDir = storage_path('app/public/og-cache');
        if (! is_dir($cacheDir)) {
            File::makeDirectory($cacheDir, 0755, true);
        }

        $cached = $cacheDir.'/'.pathinfo($filename, PATHINFO_FILENAME).'.jpg';

        if (! is_file($cached) || filemtime($cached) < filemtime($source)) {
            if (! $this->generate($source, $cached)) {
                return null;
            }
        }

        return $cached;
    }

    public function isAllowedFilename(string $filename): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9._-]+\.(jpe?g|png|webp)$/i', $filename);
    }

    private function generate(string $source, string $destination): bool
    {
        if (! extension_loaded('gd')) {
            return false;
        }

        $info = @getimagesize($source);
        if ($info === false) {
            return false;
        }

        $sourceImage = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            default => false,
        };

        if ($sourceImage === false) {
            return false;
        }

        $srcW = imagesx($sourceImage);
        $srcH = imagesy($sourceImage);

        $scale = max(self::WIDTH / $srcW, self::HEIGHT / $srcH);
        $tmpW = (int) round($srcW * $scale);
        $tmpH = (int) round($srcH * $scale);

        $resized = imagecreatetruecolor($tmpW, $tmpH);
        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $tmpW, $tmpH, $srcW, $srcH);
        imagedestroy($sourceImage);

        $cropX = (int) max(0, ($tmpW - self::WIDTH) / 2);
        $cropY = (int) max(0, ($tmpH - self::HEIGHT) / 2);

        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imagecopy($canvas, $resized, 0, 0, $cropX, $cropY, self::WIDTH, self::HEIGHT);
        imagedestroy($resized);

        $saved = imagejpeg($canvas, $destination, self::JPEG_QUALITY);
        imagedestroy($canvas);

        return $saved;
    }
}
