<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::query()
            ->with('author:id,name')
            ->latest('published_at')
            ->latest('id')
            ->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateVideo($request);
        $data['youtube_video_id'] = $this->extractYoutubeVideoId($data['youtube_url']);
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?? now()) : null;
        $data['author_id'] = auth()->id();

        Video::create($data);

        return redirect()->route('admin.videos.index')->with('success', 'Vidéo 2IM TV créée.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $data = $this->validateVideo($request);
        $data['youtube_video_id'] = $this->extractYoutubeVideoId($data['youtube_url']);
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?? $video->published_at ?? now()) : null;

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', 'Vidéo 2IM TV mise à jour.');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return back()->with('success', 'Vidéo 2IM TV supprimée.');
    }

    private function validateVideo(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'youtube_url' => ['required', 'url', 'max:1000'],
            'description' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function extractYoutubeVideoId(string $url): string
    {
        $id = youtube_video_id_from_text($url);
        abort_unless($id !== null, 422, 'Lien YouTube invalide. Utilisez une URL YouTube valide.');

        return $id;
    }
}

