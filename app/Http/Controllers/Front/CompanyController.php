<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('category')->where('is_active', true)->latest()->paginate(12);
        return view('front.companies.index', compact('companies'));
    }

    public function show(string $locale, string $slug)
    {
        $company = Company::with(['category.parent', 'projects'])->where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('front.companies.show', compact('company'));
    }
}
