@extends('layouts.admin')

@section('content')
    @php
        $parts = preg_split('/\s+/', trim(auth()->user()->name ?? ''), 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';
        $s = $siteSettings ?? collect();
        $val = fn (string $key) => old("settings.$key", $s->get($key, ''));
    @endphp

    <div class="admin-hero mb-4">
        <div>
            <h1 class="h3 mb-1">Paramètres</h1>
            <p class="text-muted mb-0">Coordonnées du site, réseaux sociaux et profil administrateur.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card card-mag">
                <div class="card-body">
                    <h2 class="h6 mb-3">Coordonnées &amp; réseaux sociaux</h2>
                    <p class="text-muted small mb-4">Ces informations sont utilisées sur le site public (en-tête, pied de page, page contact). Laissez un champ vide pour utiliser la valeur par défaut (.env / configuration).</p>
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
                        @csrf
                        <input type="hidden" name="section" value="site">

                        <div class="col-md-6">
                            <label class="form-label">E-mail de contact</label>
                            <input type="text" name="settings[contact_email]" class="form-control @error('settings.contact_email') is-invalid @enderror" value="{{ $val('contact_email') }}" placeholder="contact@exemple.ci" autocomplete="off">
                            @error('settings.contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="settings[contact_phone]" class="form-control" value="{{ $val('contact_phone') }}" placeholder="+225 …">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresse (affichage)</label>
                            <input type="text" name="settings[contact_address]" class="form-control" value="{{ $val('contact_address') }}" placeholder="Ville, pays">
                        </div>

                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-12"><span class="text-muted small fw-semibold">URLs des réseaux (https://…)</span></div>

                        <div class="col-md-6">
                            <label class="form-label">Facebook</label>
                            <input type="text" name="settings[social_facebook]" class="form-control" value="{{ $val('social_facebook') }}" placeholder="https://…" inputmode="url" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">X (Twitter)</label>
                            <input type="text" name="settings[social_x]" class="form-control" value="{{ $val('social_x') }}" placeholder="https://…" inputmode="url" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">LinkedIn</label>
                            <input type="text" name="settings[social_linkedin]" class="form-control" value="{{ $val('social_linkedin') }}" placeholder="https://…" inputmode="url" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Instagram</label>
                            <input type="text" name="settings[social_instagram]" class="form-control" value="{{ $val('social_instagram') }}" placeholder="https://…" inputmode="url" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">YouTube</label>
                            <input type="text" name="settings[social_youtube]" class="form-control" value="{{ $val('social_youtube') }}" placeholder="https://www.youtube.com/…" inputmode="url" autocomplete="off">
                        </div>

                        <div class="col-12">
                            <button class="btn btn-ivm" type="submit">Enregistrer les infos du site</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Mon profil</h2>
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
                        @csrf
                        <input type="hidden" name="section" value="profile">

                        <div class="col-12 col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $firstName) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $lastName) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer" autocomplete="new-password">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-secondary" type="submit">Mettre à jour mon profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
