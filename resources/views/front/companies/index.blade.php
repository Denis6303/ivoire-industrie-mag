@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Entreprises</h1>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($companies as $company)
            <a class="rounded bg-white p-4 shadow" href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a>
        @endforeach
    </div>
    <div class="mt-6">{{ $companies->links() }}</div>
@endsection
