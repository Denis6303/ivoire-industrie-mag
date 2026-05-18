@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Editer la catégorie</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
    </div>

    <div class="card card-mag">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="row g-3" id="category-form">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input class="form-control" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Slug (optionnel)</label>
                    <input class="form-control" name="slug" value="{{ old('slug', $category->slug) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Couleur</label>
                    @php
                        $defaultColor = old('color', $category->color) ?: '#ff7800';
                        if (! preg_match('/^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$/', $defaultColor)) {
                            $defaultColor = '#ff7800';
                        }
                        $pickerVal = $defaultColor;
                        if (strlen($defaultColor) === 4) {
                            $h = substr($defaultColor, 1);
                            $pickerVal = '#' . $h[0].$h[0].$h[1].$h[1].$h[2].$h[2];
                        }
                    @endphp
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <input type="color" id="category-color-picker" class="form-control form-control-color p-1" value="{{ $pickerVal }}" title="Choisir une couleur" style="width:3rem;height:2.5rem;">
                        <input type="text" name="color" id="category-color-hex" class="form-control font-monospace" style="max-width:8rem;" value="{{ $defaultColor }}" maxlength="7" pattern="^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$" autocomplete="off">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="category-color-generate">Générer</button>
                        <span class="rounded border d-inline-block flex-shrink-0" id="category-color-swatch" title="Aperçu" style="width:2.5rem;height:2.5rem;background:{{ $pickerVal }};"></span>
                    </div>
                    @error('color')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    <div class="form-text">Le carré affiche la couleur choisie ou générée.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Parent (optionnel)</label>
                    <select class="form-select" name="parent_id">
                        <option value="">Aucun</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-ivm" type="submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const picker = document.getElementById('category-color-picker');
            const hex = document.getElementById('category-color-hex');
            const swatch = document.getElementById('category-color-swatch');
            const btnGen = document.getElementById('category-color-generate');
            if (!picker || !hex || !swatch) return;

            function expandHex3(h) {
                if (h.length !== 3) return h;
                return h[0] + h[0] + h[1] + h[1] + h[2] + h[2];
            }

            function normalizeHex(v) {
                v = (v || '').trim();
                if (!/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(v)) return null;
                const inner = v.slice(1);
                const six = inner.length === 3 ? expandHex3(inner) : inner;
                return '#' + six.toUpperCase();
            }

            function applyColor(raw) {
                const n = normalizeHex(raw);
                if (!n) return;
                hex.value = n;
                picker.value = n;
                swatch.style.background = n;
            }

            picker.addEventListener('input', function () {
                applyColor(picker.value);
            });

            hex.addEventListener('input', function () {
                const n = normalizeHex(hex.value);
                if (n) {
                    picker.value = n;
                    swatch.style.background = n;
                }
            });

            hex.addEventListener('change', function () {
                const n = normalizeHex(hex.value);
                if (n) applyColor(n);
            });

            if (btnGen) {
                btnGen.addEventListener('click', function () {
                    const r = '#' + Math.floor(Math.random() * 0xffffff).toString(16).padStart(6, '0');
                    applyColor(r);
                });
            }

            applyColor(hex.value);

            const form = document.getElementById('category-form');
            if (form) {
                form.addEventListener('submit', function () {
                    applyColor(picker.value || hex.value);
                });
            }
        });
    </script>
@endsection
