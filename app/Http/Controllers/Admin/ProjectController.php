<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IndustrialProject;
use App\Models\IndustrySector;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = IndustrialProject::with(['sector', 'company'])->latest()->paginate(20);
        return view('admin.projets.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sectors = IndustrySector::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        return view('admin.projets.create', compact('sectors', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:industrial_projects,slug'],
            'description' => ['nullable', 'string'],
            'investment' => ['nullable', 'numeric'],
            'jobs_created' => ['nullable', 'integer'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:planned,in_progress,completed,suspended'],
            'industry_sector_id' => ['nullable', 'exists:industry_sectors,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        IndustrialProject::create($data);

        return redirect()->route('admin.projets.index')->with('success', 'Projet créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IndustrialProject $projet)
    {
        $projet->load(['sector', 'company']);
        return view('admin.projets.show', ['projet' => $projet]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IndustrialProject $projet)
    {
        $sectors = IndustrySector::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        return view('admin.projets.edit', compact('projet', 'sectors', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IndustrialProject $projet)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('industrial_projects', 'slug')->ignore($projet->id),
            ],
            'description' => ['nullable', 'string'],
            'investment' => ['nullable', 'numeric'],
            'jobs_created' => ['nullable', 'integer'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:planned,in_progress,completed,suspended'],
            'industry_sector_id' => ['nullable', 'exists:industry_sectors,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);

        $projet->update($data);
        return redirect()->route('admin.projets.index')->with('success', 'Projet mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndustrialProject $projet)
    {
        $projet->delete();
        return back()->with('success', 'Projet supprimé.');
    }
}
