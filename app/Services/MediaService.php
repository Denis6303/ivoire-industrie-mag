<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    public function upload(UploadedFile $file, string $directory = 'media', string $disk = 'public'): array
    {
        $path = $file->store($directory, $disk);

        return [
            'filename' => basename($path),
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'disk' => $disk,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ];
    }
}
