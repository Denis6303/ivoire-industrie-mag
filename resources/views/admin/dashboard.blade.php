@extends('layouts.admin')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Dashboard</h1>
    <div class="mb-6 grid gap-4 md:grid-cols-4">
        <div class="rounded bg-white p-4 shadow">Articles publies: {{ $stats['published_articles'] }}</div>
        <div class="rounded bg-white p-4 shadow">Vues aujourd'hui: {{ $stats['views_today'] }}</div>
        <div class="rounded bg-white p-4 shadow">Abonnes: {{ $stats['newsletter_subscribers'] }}</div>
        <div class="rounded bg-white p-4 shadow">Commentaires en attente: {{ $stats['pending_comments'] }}</div>
    </div>
@endsection
