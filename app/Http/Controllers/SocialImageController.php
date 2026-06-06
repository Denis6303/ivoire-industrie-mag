<?php

namespace App\Http\Controllers;

use App\Services\SocialImageService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SocialImageController extends Controller
{
    public function __construct(private SocialImageService $socialImages) {}

    public function show(string $filename): BinaryFileResponse
    {
        $cached = $this->socialImages->resolveCachedPath($filename);

        abort_unless($cached !== null && is_file($cached), 404);

        return response()->file($cached, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
