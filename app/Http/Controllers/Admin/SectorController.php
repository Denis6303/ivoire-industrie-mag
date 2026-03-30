<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IndustrySector;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sectors = IndustrySector::orderBy('order')->paginate(20);
        return view('admin.secteurs.index', compact('sectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.secteurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:industry_sectors,slug'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        $data['is_active'] = $request->boolean('is_active');

        IndustrySector::create($data);

        return redirect()->route('admin.secteurs.index')->with('success', 'Secteur créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IndustrySector $secteur)
    {
        $secteur->load(['articles', 'companies', 'projects']);
        return view('admin.secteurs.show', ['secteur' => $secteur]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IndustrySector $secteur)
    {
        return view('admin.secteurs.edit', compact('secteur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IndustrySector $secteur)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('industry_sectors', 'slug')->ignore($secteur->id),
            ],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'order' => ['nullable', 'integer'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $secteur->update($data);
        return redirect()->route('admin.secteurs.index')->with('success', 'Secteur mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndustrySector $secteur)
    {
        $secteur->delete();
        return back()->with('success', 'Secteur supprimé.');
    }
}
