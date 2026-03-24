<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\IndustrialProject;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = IndustrialProject::with(['sector', 'company'])->latest()->paginate(12);
        return view('front.projects.index', compact('projects'));
    }
}
