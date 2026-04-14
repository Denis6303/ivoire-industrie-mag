<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with('sector')->latest()->paginate(10);
        return view('admin.entreprises.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sectors = \App\Models\IndustrySector::orderBy('name')->get();
        return view('admin.entreprises.create', compact('sectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:companies,slug'],
            'logo' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'industry_sector_id' => ['nullable', 'exists:industry_sectors,id'],
            'logo_file' => ['nullable', 'image', 'max:5120'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('companies', 'public');
            $data['logo'] = Storage::disk('public')->url($path);
        }

        Company::create($data);

        return redirect()->route('admin.entreprises.index')->with('success', 'Entreprise créée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $entreprise)
    {
        $entreprise->load(['sector', 'projects']);
        return view('admin.entreprises.show', ['entreprise' => $entreprise]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $entreprise)
    {
        $sectors = \App\Models\IndustrySector::orderBy('name')->get();
        return view('admin.entreprises.edit', compact('entreprise', 'sectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $entreprise)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('companies', 'slug')->ignore($entreprise->id),
            ],
            'logo' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'industry_sector_id' => ['nullable', 'exists:industry_sectors,id'],
            'logo_file' => ['nullable', 'image', 'max:5120'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('companies', 'public');
            $data['logo'] = Storage::disk('public')->url($path);
        }

        $entreprise->update($data);

        return redirect()->route('admin.entreprises.index')->with('success', 'Entreprise mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $entreprise)
    {
        $entreprise->delete();
        return back()->with('success', 'Entreprise supprimée.');
    }
}
