<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Advertisement::orderByDesc('is_active')->orderByDesc('id')->paginate(20);
        return view('admin.publicites.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.publicites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:1024'],
            'image_file' => ['nullable', 'image', 'max:10240'],
            'link_url' => ['required', 'string', 'max:1024'],
            'position' => ['required', 'in:header,sidebar,in_article,footer'],
            'is_active' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $imageUrl = $data['image_url'];
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('ads', 'public');
            $imageUrl = Storage::disk('public')->url($path);
        }

        if (! $imageUrl) {
            return back()->withErrors(['image_url' => 'Image requise.'])->withInput();
        }

        $data['image_url'] = $imageUrl;
        $data['is_active'] = $request->boolean('is_active');

        Advertisement::create($data);

        return redirect()->route('admin.publicites.index')->with('success', 'Publicité créée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Advertisement $publicite)
    {
        return view('admin.publicites.show', ['publicite' => $publicite]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advertisement $publicite)
    {
        return view('admin.publicites.edit', ['publicite' => $publicite]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Advertisement $publicite)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:1024'],
            'image_file' => ['nullable', 'image', 'max:10240'],
            'link_url' => ['nullable', 'string', 'max:1024'],
            'position' => ['required', 'in:header,sidebar,in_article,footer'],
            'is_active' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $imageUrl = $data['image_url'] ?: $publicite->image_url;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('ads', 'public');
            $imageUrl = Storage::disk('public')->url($path);
        }

        $data['image_url'] = $imageUrl;
        $data['is_active'] = $request->boolean('is_active');
        $data['link_url'] = $data['link_url'] ?: $publicite->link_url;

        $publicite->update($data);

        return redirect()->route('admin.publicites.index')->with('success', 'Publicité mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advertisement $publicite)
    {
        $publicite->delete();
        return back()->with('success', 'Publicité supprimée.');
    }
}
