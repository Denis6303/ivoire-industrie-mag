<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::with(['author', 'category'])->latest();
        if (auth()->user()?->role === 'editor') {
            $query->where('author_id', auth()->id());
        }

        $search = trim((string) $request->string('q'));
        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $status = (string) $request->string('status');
        if (in_array($status, ['published', 'draft'], true)) {
            $query->where('status', $status);
        }

        $categoryId = (int) $request->integer('category_id');
        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }

        $statsQuery = Article::query();
        if (auth()->user()?->role === 'editor') {
            $statsQuery->where('author_id', auth()->id());
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'published' => (clone $statsQuery)->where('status', 'published')->count(),
            'draft' => (clone $statsQuery)->where('status', 'draft')->count(),
            'featured' => (clone $statsQuery)->where('is_featured', true)->count(),
        ];

        $articles = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.articles.index', compact('articles', 'categories', 'stats', 'search', 'status', 'categoryId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get(['id', 'name']);
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function createBreve()
    {
        return view('admin.articles.create-breve');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $supportsExtraImages = $this->supportsExtraArticleImages();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'signature' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'cover_file' => ['nullable', 'image', 'max:10240'],
            'cover_alt' => ['nullable', 'string', 'max:255'],
            'cover_file_secondary' => $supportsExtraImages ? ['nullable', 'image', 'max:10240'] : ['nullable'],
            'cover_alt_secondary' => $supportsExtraImages ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'cover_file_tertiary' => $supportsExtraImages ? ['nullable', 'image', 'max:10240'] : ['nullable'],
            'cover_alt_tertiary' => $supportsExtraImages ? ['nullable', 'string', 'max:255'] : ['nullable'],
        ]);

        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(6));

        unset($data['cover_file'], $data['cover_file_secondary'], $data['cover_file_tertiary']);
        if ($supportsExtraImages) {
            $data['secondary_alt'] = $data['cover_alt_secondary'] ?? null;
            $data['tertiary_alt'] = $data['cover_alt_tertiary'] ?? null;
        }
        unset($data['cover_alt_secondary'], $data['cover_alt_tertiary']);

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

            $data['cover_image'] = '/storage/'.ltrim($uploaded['path'], '/');
        }

        if ($supportsExtraImages && $request->hasFile('cover_file_secondary')) {
            $uploaded = app(MediaService::class)->upload($request->file('cover_file_secondary'), 'media', 'public');
            $media = Media::create([
                'filename' => $uploaded['filename'],
                'original_name' => $uploaded['original_name'],
                'path' => $uploaded['path'],
                'url' => $uploaded['url'],
                'disk' => $uploaded['disk'],
                'mime_type' => $uploaded['mime_type'],
                'type' => 'image',
                'size' => $uploaded['size'],
                'alt' => $data['cover_alt_secondary'] ?? null,
                'caption' => null,
                'uploaded_by' => auth()->id(),
            ]);

            $data['secondary_image'] = '/storage/'.ltrim($uploaded['path'], '/');
        }

        if ($supportsExtraImages && $request->hasFile('cover_file_tertiary')) {
            $uploaded = app(MediaService::class)->upload($request->file('cover_file_tertiary'), 'media', 'public');
            $media = Media::create([
                'filename' => $uploaded['filename'],
                'original_name' => $uploaded['original_name'],
                'path' => $uploaded['path'],
                'url' => $uploaded['url'],
                'disk' => $uploaded['disk'],
                'mime_type' => $uploaded['mime_type'],
                'type' => 'image',
                'size' => $uploaded['size'],
                'alt' => $data['cover_alt_tertiary'] ?? null,
                'caption' => null,
                'uploaded_by' => auth()->id(),
            ]);

            $data['tertiary_image'] = '/storage/'.ltrim($uploaded['path'], '/');
        }

        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);

        $article = Article::create($data);
        $article->tags()->sync($tagIds);
        return redirect()->route('admin.articles.index')->with('success', 'Article créé.');
    }

    public function storeBreve(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'signature' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'cover_file' => ['nullable', 'image', 'max:10240'],
            'cover_alt' => ['nullable', 'string', 'max:255'],
        ]);

        $breveCategory = $this->resolveBreveCategory();

        $payload = [
            'title' => $data['title'],
            'signature' => $data['signature'] ?? null,
            'content' => $data['content'],
            'excerpt' => Str::limit(strip_tags($data['content']), 220),
            'type' => 'breve',
            'status' => 'draft',
            'author_id' => auth()->id(),
            'category_id' => $breveCategory->id,
            'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(6)),
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
                'caption' => null,
                'uploaded_by' => auth()->id(),
            ]);

            $payload['cover_image'] = '/storage/'.ltrim($uploaded['path'], '/');
        }

        Article::create($payload);

        return redirect()->route('admin.articles.index')->with('success', 'Brève enregistrée en brouillon.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $this->ensureEditorOwnsArticle($article);
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $this->ensureEditorOwnsArticle($article);
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get(['id', 'name']);
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $this->ensureEditorOwnsArticle($article);
        $supportsExtraImages = $this->supportsExtraArticleImages();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'signature' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['required', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'cover_file' => ['nullable', 'image', 'max:10240'],
            'cover_alt' => ['nullable', 'string', 'max:255'],
            'cover_file_secondary' => $supportsExtraImages ? ['nullable', 'image', 'max:10240'] : ['nullable'],
            'cover_alt_secondary' => $supportsExtraImages ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'cover_file_tertiary' => $supportsExtraImages ? ['nullable', 'image', 'max:10240'] : ['nullable'],
            'cover_alt_tertiary' => $supportsExtraImages ? ['nullable', 'string', 'max:255'] : ['nullable'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'remove_secondary_image' => ['nullable', 'boolean'],
            'remove_tertiary_image' => ['nullable', 'boolean'],
        ]);

        unset($data['cover_file'], $data['cover_file_secondary'], $data['cover_file_tertiary']);
        if ($supportsExtraImages) {
            $data['secondary_alt'] = $data['cover_alt_secondary'] ?? null;
            $data['tertiary_alt'] = $data['cover_alt_tertiary'] ?? null;
        }
        unset($data['cover_alt_secondary'], $data['cover_alt_tertiary']);

        if (!empty($data['remove_cover_image'])) {
            $data['cover_image'] = null;
            $data['cover_alt'] = null;
        }
        if ($supportsExtraImages && !empty($data['remove_secondary_image'])) {
            $data['secondary_image'] = null;
            $data['secondary_alt'] = null;
        }
        if ($supportsExtraImages && !empty($data['remove_tertiary_image'])) {
            $data['tertiary_image'] = null;
            $data['tertiary_alt'] = null;
        }
        unset($data['remove_cover_image'], $data['remove_secondary_image'], $data['remove_tertiary_image']);

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

            $data['cover_image'] = '/storage/'.ltrim($uploaded['path'], '/');
        }

        if ($supportsExtraImages && $request->hasFile('cover_file_secondary')) {
            $uploaded = app(MediaService::class)->upload($request->file('cover_file_secondary'), 'media', 'public');
            $media = Media::create([
                'filename' => $uploaded['filename'],
                'original_name' => $uploaded['original_name'],
                'path' => $uploaded['path'],
                'url' => $uploaded['url'],
                'disk' => $uploaded['disk'],
                'mime_type' => $uploaded['mime_type'],
                'type' => 'image',
                'size' => $uploaded['size'],
                'alt' => $data['cover_alt_secondary'] ?? null,
                'caption' => null,
                'uploaded_by' => auth()->id(),
            ]);

            $data['secondary_image'] = '/storage/'.ltrim($uploaded['path'], '/');
        }

        if ($supportsExtraImages && $request->hasFile('cover_file_tertiary')) {
            $uploaded = app(MediaService::class)->upload($request->file('cover_file_tertiary'), 'media', 'public');
            $media = Media::create([
                'filename' => $uploaded['filename'],
                'original_name' => $uploaded['original_name'],
                'path' => $uploaded['path'],
                'url' => $uploaded['url'],
                'disk' => $uploaded['disk'],
                'mime_type' => $uploaded['mime_type'],
                'type' => 'image',
                'size' => $uploaded['size'],
                'alt' => $data['cover_alt_tertiary'] ?? null,
                'caption' => null,
                'uploaded_by' => auth()->id(),
            ]);

            $data['tertiary_image'] = '/storage/'.ltrim($uploaded['path'], '/');
        }

        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);

        $article->update($data);
        $article->tags()->sync($tagIds);
        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $this->ensureEditorOwnsArticle($article);
        $article->delete();
        return back()->with('success', 'Article supprimé.');
    }

    public function publish(Article $article)
    {
        $this->ensureEditorOwnsArticle($article);
        $article->update(['status' => 'published', 'published_at' => now()]);
        return back()->with('success', 'Article publié.');
    }

    public function unpublish(Article $article)
    {
        $this->ensureEditorOwnsArticle($article);
        $article->update(['status' => 'draft', 'published_at' => null]);
        return back()->with('success', 'Article remis en brouillon.');
    }

    private function ensureEditorOwnsArticle(Article $article): void
    {
        if (auth()->user()?->role === 'editor' && $article->author_id !== auth()->id()) {
            abort(403, 'Vous ne pouvez gérer que vos propres contenus.');
        }
    }

    private function resolveBreveCategory(): Category
    {
        return Category::firstOrCreate(
            ['slug' => 'breve'],
            [
                'name' => 'Brève',
                'description' => 'Articles au format brève.',
                'color' => '#ff7800',
                'order' => 0,
            ]
        );
    }

    private function supportsExtraArticleImages(): bool
    {
        return Schema::hasColumn('articles', 'secondary_image')
            && Schema::hasColumn('articles', 'secondary_alt')
            && Schema::hasColumn('articles', 'tertiary_image')
            && Schema::hasColumn('articles', 'tertiary_alt');
    }
}
