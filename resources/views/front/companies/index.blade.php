@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Entreprises</h1>
    <div class="row g-3">
        @foreach($companies as $company)
            <div class="col-md-6 col-lg-4">
                <a class="card card-body card-mag text-decoration-none text-dark h-100" href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $companies->links() }}</div>
@endsection
