@extends('layouts.admin')

@php
    $s = $report['stat'];
    $fmt = fn ($n) => number_format((int) $n, 0, ',', ' ');
    $pct = fn ($n) => number_format((float) $n, 1, ',', ' ').' %';
    $sec = fn ($n) => gmdate('i\m s\s', max(0, (int) $n));
    $sourceLabels = [
        'direct' => 'Direct',
        'organic' => 'SEO / moteurs',
        'social' => 'Réseaux sociaux',
        'referral' => 'Sites référents',
        'campaign' => 'Campagnes UTM',
        'homepage' => 'Page d\'accueil',
        'category' => 'Rubrique',
        'internal' => 'Navigation interne',
        'newsletter' => 'Newsletter',
    ];
@endphp

@section('content')
    <div class="admin-hero mb-4">
        <div>
            <h1 class="h3 mb-1">Statistiques article</h1>
            <p class="text-muted mb-0">{{ $article->title }}</p>
            <div class="small text-muted mt-1">
                {{ optional($article->category)->name ?? '—' }} · {{ optional($article->author)->name ?? '—' }}
                · Publié {{ optional($article->published_at)->format('d/m/Y') ?? '—' }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Retour</a>
            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-outline-primary">Modifier</a>
            <a href="{{ route('articles.show', [config('app.locale', 'fr'), $article->slug]) }}" target="_blank" class="btn btn-ivm">Voir en ligne</a>
        </div>
    </div>

    {{-- KPI principaux --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card card-mag h-100 admin-stat-card admin-stat-primary">
                <div class="card-body">
                    <div class="text-muted small">Vues totales</div>
                    <div class="h3 fw-bold mb-0">{{ $fmt($s->views_total) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card card-mag h-100 admin-stat-card admin-stat-info">
                <div class="card-body">
                    <div class="text-muted small">Visiteurs uniques</div>
                    <div class="h3 fw-bold mb-0">{{ $fmt($s->views_unique) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card card-mag h-100 admin-stat-card admin-stat-success">
                <div class="card-body">
                    <div class="text-muted small">Lectures qualifiées</div>
                    <div class="h3 fw-bold mb-0">{{ $fmt($s->qualified_reads) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card card-mag h-100 admin-stat-card admin-stat-warning">
                <div class="card-body">
                    <div class="text-muted small">Partages</div>
                    <div class="h3 fw-bold mb-0">{{ $fmt($s->shares_total) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card card-mag h-100 admin-stat-card admin-stat-visitors">
                <div class="card-body">
                    <div class="text-muted small">Commentaires</div>
                    <div class="h3 fw-bold mb-0">{{ $fmt($report['comments']['approved']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card card-mag h-100 admin-stat-card admin-stat-primary">
                <div class="card-body">
                    <div class="text-muted small">Score perf.</div>
                    <div class="h3 fw-bold mb-0">{{ $report['editorial']['performance_score'] }}/100</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card card-mag admin-table-card h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Vues — 30 derniers jours</h2>
                    <div class="admin-chart-wrap">
                        <canvas id="viewsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-mag admin-table-card h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Appareils</h2>
                    <div class="admin-chart-wrap">
                        <canvas id="devicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Audience --}}
    <div class="card card-mag mb-4">
        <div class="card-body">
            <h2 class="h6 mb-3">Audience & trafic</h2>
            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <ul class="list-unstyled admin-kpi-list mb-0">
                        <li class="admin-kpi-item"><span>Vues retour</span><strong>{{ $fmt($s->views_returning) }}</strong></li>
                        <li class="admin-kpi-item"><span>Pic journalier</span><strong>{{ $fmt($s->peak_views_count) }} @if($s->peak_views_date)<small class="text-muted">({{ $s->peak_views_date->format('d/m/Y') }})</small>@endif</strong></li>
                        <li class="admin-kpi-item"><span>Première vue</span><strong>{{ optional($s->first_view_at)->format('d/m/Y H:i') ?? '—' }}</strong></li>
                        <li class="admin-kpi-item"><span>Dernière vue</span><strong>{{ optional($s->last_view_at)->format('d/m/Y H:i') ?? '—' }}</strong></li>
                        <li class="admin-kpi-item"><span>Rebonds</span><strong>{{ $fmt($s->bounces) }}</strong></li>
                        <li class="admin-kpi-item"><span>Crawlers OG (preview)</span><strong>{{ $fmt($s->og_crawler_hits) }}</strong></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-4">
                    <ul class="list-unstyled admin-kpi-list mb-0">
                        <li class="admin-kpi-item"><span>FR / EN</span><strong>{{ $fmt($s->views_fr) }} / {{ $fmt($s->views_en) }}</strong></li>
                        <li class="admin-kpi-item"><span>Mobile / Tablette / Desktop</span><strong>{{ $fmt($s->views_mobile) }} / {{ $fmt($s->views_tablet) }} / {{ $fmt($s->views_desktop) }}</strong></li>
                        <li class="admin-kpi-item"><span>SEO organique</span><strong>{{ $fmt($s->views_organic) }}</strong></li>
                        <li class="admin-kpi-item"><span>Réseaux sociaux</span><strong>{{ $fmt($s->views_social) }}</strong></li>
                        <li class="admin-kpi-item"><span>Direct</span><strong>{{ $fmt($s->views_direct) }}</strong></li>
                        <li class="admin-kpi-item"><span>Campagnes UTM</span><strong>{{ $fmt($s->views_campaign) }}</strong></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-4">
                    <ul class="list-unstyled admin-kpi-list mb-0">
                        <li class="admin-kpi-item"><span>Page d'accueil</span><strong>{{ $fmt($s->views_homepage) }}</strong></li>
                        <li class="admin-kpi-item"><span>Rubrique</span><strong>{{ $fmt($s->views_category) }}</strong></li>
                        <li class="admin-kpi-item"><span>Navigation interne</span><strong>{{ $fmt($s->views_internal) }}</strong></li>
                        <li class="admin-kpi-item"><span>Newsletter</span><strong>{{ $fmt($s->views_newsletter) }}</strong></li>
                        <li class="admin-kpi-item"><span>Référents externes</span><strong>{{ $fmt($s->views_referral) }}</strong></li>
                        <li class="admin-kpi-item"><span>Vues 24 h / 7 j / 30 j</span><strong>{{ $fmt($report['editorial']['views_24h']) }} / {{ $fmt($report['editorial']['views_7d']) }} / {{ $fmt($report['editorial']['views_30d']) }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Engagement --}}
    <div class="card card-mag mb-4">
        <div class="card-body">
            <h2 class="h6 mb-3">Engagement & lecture</h2>
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="text-muted small">Temps moyen sur page</div>
                    <div class="fw-bold fs-5">{{ $sec($report['engagement']['avg_time_seconds']) }}</div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-muted small">Taux d'engagement</div>
                    <div class="fw-bold fs-5">{{ $pct($report['engagement']['engagement_rate']) }}</div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-muted small">Taux de partage</div>
                    <div class="fw-bold fs-5">{{ $pct($report['engagement']['share_rate']) }}</div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="text-muted small">Taux de commentaire</div>
                    <div class="fw-bold fs-5">{{ $pct($report['comments']['rate']) }}</div>
                </div>
            </div>
            <hr>
            <div class="row g-2">
                @foreach ([25 => $s->scroll_25, 50 => $s->scroll_50, 75 => $s->scroll_75, 100 => $s->scroll_100] as $mark => $count)
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">Scroll {{ $mark }} %</div>
                        <div class="fw-semibold">{{ $fmt($count) }} <span class="text-muted">({{ $pct($report['engagement']['scroll_'.$mark.'_rate']) }})</span></div>
                        <div class="progress mt-1" style="height:6px;">
                            <div class="progress-bar bg-primary" style="width:{{ min(100, $report['engagement']['scroll_'.$mark.'_rate']) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>
            <div class="row g-3">
                <div class="col-md-4"><span class="text-muted">Liens internes</span> <strong>{{ $fmt($s->clicks_internal_links) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">Liens externes</span> <strong>{{ $fmt($s->clicks_external_links) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">Articles liés</span> <strong>{{ $fmt($s->clicks_related) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">Image couverture</span> <strong>{{ $fmt($s->clicks_cover_image) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">Images inline</span> <strong>{{ $fmt($s->clicks_secondary_image) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">CTA newsletter</span> <strong>{{ $fmt($s->clicks_newsletter) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">Liens emplois</span> <strong>{{ $fmt($s->clicks_jobs) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">Liens entreprises</span> <strong>{{ $fmt($s->clicks_companies) }}</strong></div>
                <div class="col-md-4"><span class="text-muted">Inscriptions newsletter</span> <strong>{{ $fmt($s->newsletter_signups) }}</strong></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Partages --}}
        <div class="col-lg-4">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Partages sociaux</h2>
                    <ul class="list-unstyled admin-kpi-list mb-0">
                        <li class="admin-kpi-item"><span>Facebook</span><strong>{{ $fmt($s->shares_facebook) }}</strong></li>
                        <li class="admin-kpi-item"><span>LinkedIn</span><strong>{{ $fmt($s->shares_linkedin) }}</strong></li>
                        <li class="admin-kpi-item"><span>X / Twitter</span><strong>{{ $fmt($s->shares_twitter) }}</strong></li>
                        <li class="admin-kpi-item"><span>WhatsApp</span><strong>{{ $fmt($s->shares_whatsapp) }}</strong></li>
                        <li class="admin-kpi-item"><span>Copier le lien</span><strong>{{ $fmt($s->shares_copy) }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Commentaires --}}
        <div class="col-lg-4">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Commentaires</h2>
                    <ul class="list-unstyled admin-kpi-list mb-0">
                        <li class="admin-kpi-item"><span>Total</span><strong>{{ $fmt($report['comments']['total']) }}</strong></li>
                        <li class="admin-kpi-item"><span>Approuvés</span><strong>{{ $fmt($report['comments']['approved']) }}</strong></li>
                        <li class="admin-kpi-item"><span>En attente</span><strong>{{ $fmt($report['comments']['pending']) }}</strong></li>
                        <li class="admin-kpi-item"><span>Réponses</span><strong>{{ $fmt($report['comments']['replies']) }}</strong></li>
                        <li class="admin-kpi-item"><span>Anonymes / Connectés</span><strong>{{ $fmt($report['comments']['guest']) }} / {{ $fmt($report['comments']['registered']) }}</strong></li>
                        <li class="admin-kpi-item"><span>Délai 1er commentaire</span><strong>@if($report['comments']['time_to_first_hours'] !== null){{ $report['comments']['time_to_first_hours'] }} h @else — @endif</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Performance éditoriale --}}
        <div class="col-lg-4">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Performance éditoriale</h2>
                    <ul class="list-unstyled admin-kpi-list mb-0">
                        <li class="admin-kpi-item"><span>Classement site</span><strong>#{{ $report['editorial']['site_rank'] }}</strong></li>
                        <li class="admin-kpi-item"><span>Moy. rubrique</span><strong>{{ $fmt($report['editorial']['category_avg_views']) }} vues</strong></li>
                        <li class="admin-kpi-item"><span>Moy. auteur</span><strong>{{ $fmt($report['editorial']['author_avg_views']) }} vues</strong></li>
                        <li class="admin-kpi-item"><span>Moy. type ({{ $article->type }})</span><strong>{{ $fmt($report['editorial']['type_avg_views']) }} vues</strong></li>
                        @if($report['editorial']['still_read_after_30d'] !== null)
                            <li class="admin-kpi-item"><span>Vues 30 derniers j.</span><strong>{{ $fmt($report['editorial']['still_read_after_30d']) }}</strong></li>
                        @endif
                        <li class="admin-kpi-item"><span>Mots</span><strong>{{ $fmt($report['editorial']['word_count']) }}</strong></li>
                        <li class="admin-kpi-item"><span>SEO meta</span><strong>{{ $report['editorial']['has_meta'] ? 'Complet' : 'Incomplet' }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card card-mag admin-table-card">
                <div class="card-body">
                    <h2 class="h6 mb-3">Sources de trafic (sessions)</h2>
                    <div class="admin-chart-wrap">
                        <canvas id="sourcesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-mag admin-table-card">
                <div class="card-body">
                    <h2 class="h6 mb-3">Top référents</h2>
                    @if($report['referrers']->isEmpty())
                        <p class="text-muted mb-0">Aucun référent enregistré pour le moment.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead><tr><th>Domaine</th><th class="text-end">Visites</th></tr></thead>
                                <tbody>
                                    @foreach($report['referrers'] as $ref)
                                        <tr>
                                            <td>{{ $ref->referrer_host }}</td>
                                            <td class="text-end">{{ $fmt($ref->hit_count) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Navigateurs</h2>
                    @forelse($report['browsers'] as $browser => $total)
                        <div class="d-flex justify-content-between small mb-1"><span>{{ $browser }}</span><strong>{{ $fmt($total) }}</strong></div>
                    @empty
                        <p class="text-muted mb-0">—</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Pays (top 10)</h2>
                    @forelse($report['countries'] as $code => $total)
                        <div class="d-flex justify-content-between small mb-1"><span>{{ $code }}</span><strong>{{ $fmt($total) }}</strong></div>
                    @empty
                        <p class="text-muted mb-0">Non disponible (nécessite Cloudflare ou en-tête pays).</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">SEO & technique</h2>
                    <ul class="list-unstyled admin-kpi-list mb-0">
                        <li class="admin-kpi-item"><span>LCP moyen</span><strong>{{ $report['web_vitals']['lcp_ms'] ? $report['web_vitals']['lcp_ms'].' ms' : '—' }}</strong></li>
                        <li class="admin-kpi-item"><span>CLS moyen</span><strong>{{ $report['web_vitals']['cls'] ?? '—' }}</strong></li>
                        <li class="admin-kpi-item"><span>INP / interactivité</span><strong>{{ $report['web_vitals']['inp_ms'] ? $report['web_vitals']['inp_ms'].' ms' : '—' }}</strong></li>
                        <li class="admin-kpi-item"><span>Échantillons Web Vitals</span><strong>{{ $fmt($report['web_vitals']['samples']) }}</strong></li>
                        <li class="admin-kpi-item"><span>Search Console</span><strong class="text-muted">Intégration à venir</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const daily = @json($chartDaily);
            const devices = @json($report['devices']);
            const sources = @json($report['sources']);
            const sourceLabels = @json($sourceLabels);

            new Chart(document.getElementById('viewsChart'), {
                type: 'line',
                data: {
                    labels: daily.map(d => d.label),
                    datasets: [
                        { label: 'Vues', data: daily.map(d => d.views), borderColor: '#ff7800', tension: 0.3 },
                        { label: 'Uniques', data: daily.map(d => d.unique), borderColor: '#243e5d', tension: 0.3 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });

            new Chart(document.getElementById('devicesChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(devices).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
                    datasets: [{ data: Object.values(devices), backgroundColor: ['#ff7800', '#243e5d', '#6c757d'] }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            new Chart(document.getElementById('sourcesChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(sources).map(k => sourceLabels[k] || k),
                    datasets: [{ label: 'Sessions', data: Object.values(sources), backgroundColor: '#243e5d' }]
                },
                options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true } } }
            });
        });
    </script>
@endsection
