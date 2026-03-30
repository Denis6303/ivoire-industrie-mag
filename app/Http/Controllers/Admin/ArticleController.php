<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with(['author', 'category'])->latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'cover_file' => ['nullable', 'image', 'max:10240'],
            'cover_alt' => ['nullable', 'string', 'max:255'],
        ]);

        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(6));

        unset($data['cover_file']);

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
                'alt' => $data['cover_alt'] ?? null,
                'caption' => null,
                'uploaded_by' => auth()->id(),
            ]);

            $data['cover_image'] = $media->url;
        }

        Article::create($data);
        return redirect()->route('admin.articles.index')->with('success', 'Article créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'cover_file' => ['nullable', 'image', 'max:10240'],
            'cover_alt' => ['nullable', 'string', 'max:255'],
        ]);

        unset($data['cover_file']);

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
                'alt' => $data['cover_alt'] ?? null,
                'caption' => null,
                'uploaded_by' => auth()->id(),
            ]);

            $data['cover_image'] = $media->url;
        }

        $article->update($data);
        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return back()->with('success', 'Article supprimé.');
    }

    public function publish(Article $article)
    {
        $article->update(['status' => 'published', 'published_at' => now()]);
        return back()->with('success', 'Article publié.');
    }
}
