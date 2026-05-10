<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('order')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::orderBy('name')->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'slug'      => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'color'     => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$/'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $data['slug']  = Str::slug($data['slug'] ?: $data['name']);
        $data['color'] = $this->normalizeColor($data['color'] ?? null);

        $parentId = $data['parent_id'] ?? null;
        $data['order']       = $this->nextOrderForParent($parentId ? (int) $parentId : null);
        $data['description'] = null;
        $data['icon']        = null;

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'articles']);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($category->id),
            ],
            'color'     => ['nullable', 'string', 'regex:/^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$/'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ]);

        $data['slug']  = Str::slug($data['slug'] ?: $data['name']);
        $data['color'] = $this->normalizeColor($data['color'] ?? null);

        $category->update([
            'name'      => $data['name'],
            'slug'      => $data['slug'],
            'color'     => $data['color'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }

    /**
     * Prochaine valeur d'ordre pour une catégorie du même niveau (même parent).
     */
    private function nextOrderForParent(?int $parentId): int
    {
        $q = Category::query();
        if ($parentId) {
            $q->where('parent_id', $parentId);
        } else {
            $q->whereNull('parent_id');
        }

        return ((int) $q->max('order')) + 1;
    }

    /**
     * Normalise une couleur hex (#RGB ou #RRGGBB) ; défaut orange 2IM.
     */
    private function normalizeColor(?string $color): string
    {
        $color = $color ? trim($color) : '';
        if ($color === '' || ! preg_match('/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/', $color)) {
            return '#FF7800';
        }
        $inner = substr($color, 1);
        if (strlen($inner) === 3) {
            $inner = $inner[0].$inner[0].$inner[1].$inner[1].$inner[2].$inner[2];
        }

        return '#'.strtoupper($inner);
    }
}
