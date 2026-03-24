@extends('layouts.admin')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Paramètres</h1>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
        @csrf
        @foreach($settings as $setting)
            <div>
                <label class="mb-1 block text-sm font-medium">{{ $setting->key }}</label>
                <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="w-full rounded border p-2">
            </div>
        @endforeach
        <button class="rounded bg-emerald-700 px-4 py-2 text-white">Sauvegarder</button>
    </form>
@endsection
