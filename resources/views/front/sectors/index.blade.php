@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Secteurs industriels</h1>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach($sectors as $sector)
            <a class="rounded bg-white p-4 shadow" href="{{ route('sectors.show', $sector->slug) }}">{{ $sector->name }}</a>
        @endforeach
    </div>
    <div class="mt-6">{{ $sectors->links() }}</div>
@endsection
