<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias = Media::orderByDesc('created_at')->paginate(24);
        return view('admin.medias.index', compact('medias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.medias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'type' => ['nullable', 'in:image,video,pdf,document'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');

        $uploaded = app(MediaService::class)->upload($file, 'media', 'public');

        $media = Media::create([
            'filename' => $uploaded['filename'],
            'original_name' => $uploaded['original_name'],
            'path' => $uploaded['path'],
            'url' => $uploaded['url'],
            'disk' => $uploaded['disk'],
            'mime_type' => $uploaded['mime_type'],
            'type' => $data['type'] ?? 'image',
            'size' => $uploaded['size'],
            'alt' => $data['alt'] ?? null,
            'caption' => $data['caption'] ?? null,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.medias.show', $media)->with('success', 'Média uploadé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return view('admin.medias.show', compact('media'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        return view('admin.medias.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        $data = $request->validate([
            'type' => ['nullable', 'in:image,video,pdf,document'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $media->update($data);

        return redirect()->route('admin.medias.show', $media)->with('success', 'Média mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        // Ne supprime pas forcément le fichier pour éviter les erreurs en prod.
        $media->delete();
        return back()->with('success', 'Média supprimé.');
    }
}
