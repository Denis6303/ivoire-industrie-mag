@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Secteurs industriels</h1>
    <div class="row g-3">
        @foreach($sectors as $sector)
            <div class="col-md-6 col-lg-4">
                <a class="card card-body card-mag text-decoration-none text-dark" href="{{ route('sectors.show', $sector->slug) }}">{{ $sector->name }}</a>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $sectors->links() }}</div>
@endsection
