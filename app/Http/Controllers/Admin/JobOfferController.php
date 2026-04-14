<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobOfferController extends Controller
{
    public function index()
    {
        $query = JobOffer::with('author')->latest();
        if (auth()->user()?->role === 'editor') {
            $query->where('author_id', auth()->id());
        }

        $offers = $query->paginate(10);

        return view('admin.jobs.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'signature' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'cover_file' => ['nullable', 'image', 'max:10240'],
            'cover_alt' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(6)),
            'signature' => $data['signature'] ?? null,
            'content' => $data['content'],
            'cover_alt' => $data['cover_alt'] ?? null,
            'author_id' => auth()->id(),
            'status' => 'draft',
        ];

        if ($request->hasFile('cover_file')) {
            $uploaded = app(MediaService::class)->upload($request->file('cover_file'), 'media', 'public');
            $media = Media::create([
                'filename' => $uploaded['filename'],
                'original_name' => $uploaded['original_name'],
                'path' => $uploaded['path'],
                'url' => $uploaded['url'],
                'disk' => $uploaded['disk'],
                'mime_type' => $uploaded['mime_type'],
                'type' => 'image',
                'size' => $uploaded['size'],
                'alt' => $payload['cover_alt'],
                'uploaded_by' => auth()->id(),
            ]);
            $payload['cover_image'] = $media->url;
        }

        JobOffer::create($payload);

        return redirect()->route('admin.emplois.index')->with('success', 'Offre enregistrée en brouillon.');
    }

    public function edit(JobOffer $job)
    {
        $this->ensureEditorOwnsOffer($job);
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobOffer $job)
    {
        $this->ensureEditorOwnsOffer($job);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'signature' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'cover_file' => ['nullable', 'image', 'max:10240'],
            'cover_alt' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [
            'title' => $data['title'],
            'signature' => $data['signature'] ?? null,
            'content' => $data['content'],
            'cover_alt' => $data['cover_alt'] ?? null,
        ];

        if ($request->hasFile('cover_file')) {
            $uploaded = app(MediaService::class)->upload($request->file('cover_file'), 'media', 'public');
            $media = Media::create([
                'filename' => $uploaded['filename'],
                'original_name' => $uploaded['original_name'],
                'path' => $uploaded['path'],
                'url' => $uploaded['url'],
                'disk' => $uploaded['disk'],
                'mime_type' => $uploaded['mime_type'],
                'type' => 'image',
                'size' => $uploaded['size'],
                'alt' => $payload['cover_alt'],
                'uploaded_by' => auth()->id(),
            ]);
            $payload['cover_image'] = $media->url;
        }

        $job->update($payload);

        return redirect()->route('admin.emplois.index')->with('success', 'Offre mise à jour.');
    }

    public function destroy(JobOffer $job)
    {
        $this->ensureEditorOwnsOffer($job);
        $job->delete();
        return back()->with('success', 'Offre supprimée.');
    }

    public function publish(JobOffer $job)
    {
        $this->ensureEditorOwnsOffer($job);
        $job->update(['status' => 'published', 'published_at' => now()]);
        return back()->with('success', 'Offre publiée.');
    }

    public function unpublish(JobOffer $job)
    {
        $this->ensureEditorOwnsOffer($job);
        $job->update(['status' => 'draft', 'published_at' => null]);
        return back()->with('success', 'Offre remise en brouillon.');
    }

    private function ensureEditorOwnsOffer(JobOffer $job): void
    {
        if (auth()->user()?->role === 'editor' && $job->author_id !== auth()->id()) {
            abort(403, 'Vous ne pouvez gérer que vos propres offres.');
        }
    }
}
