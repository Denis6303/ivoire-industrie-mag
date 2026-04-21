@extends('layouts.admin')

@section('content')
    <div class="admin-hero mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Vue d'ensemble de votre activité éditoriale.</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-ivm">
            <i class="fa-regular fa-pen-to-square me-2"></i>Gérer les articles
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="text-muted small">Articles publiés</div>
                        <i class="fa-regular fa-newspaper admin-stat-icon"></i>
                    </div>
                    <div class="display-6 fw-bold">{{ $stats['published_articles'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-info">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="text-muted small">Vues aujourd'hui</div>
                        <i class="fa-regular fa-eye admin-stat-icon"></i>
                    </div>
                    <div class="display-6 fw-bold">{{ $stats['views_today'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-success">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="text-muted small">Abonnés newsletter</div>
                        <i class="fa-regular fa-envelope admin-stat-icon"></i>
                    </div>
                    <div class="display-6 fw-bold">{{ $stats['newsletter_subscribers'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card card-mag h-100 admin-stat-card admin-stat-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="text-muted small">Commentaires</div>
                        <i class="fa-regular fa-comments admin-stat-icon"></i>
                    </div>
                    <div class="display-6 fw-bold">{{ $stats['total_comments'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card card-mag">
                <div class="card-body">
                    <h3 class="h6 mb-3">Actions rapides</h3>
                    <div class="admin-quick-actions">
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-sm admin-quick-btn admin-quick-btn-orange">Nouveau contenu</a>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin'], true))
                            <a href="{{ route('admin.commentaires.index') }}" class="btn btn-sm admin-quick-btn admin-quick-btn-blue">Modérer les commentaires</a>
                            <a href="{{ route('admin.newsletter.index') }}" class="btn btn-sm admin-quick-btn admin-quick-btn-green">Gérer la newsletter</a>
                            <a href="{{ route('admin.settings') }}" class="btn btn-sm admin-quick-btn admin-quick-btn-navy">Mettre à jour les paramètres</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4 align-items-start">
        <div class="col-12">
            <div class="card card-mag admin-table-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h6 mb-0">Performance éditoriale (7 derniers jours)</h2>
                    </div>
                    <div class="admin-chart-wrap">
                        <canvas id="dashboardChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h3 class="h6 mb-3">Répartition des contenus</h3>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Publié</span>
                            <span>{{ number_format($stats['published_rate'], 1, ',', ' ') }}%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Taux publié" aria-valuenow="{{ $stats['published_rate'] }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-success" style="width: {{ $stats['published_rate'] }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Brouillon</span>
                            <span>{{ number_format($stats['draft_rate'], 1, ',', ' ') }}%</span>
                        </div>
                        <div class="progress" role="progressbar" aria-label="Taux brouillon" aria-valuenow="{{ $stats['draft_rate'] }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['draft_rate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card card-mag h-100">
                <div class="card-body">
                    <h3 class="h6 mb-3">Indicateurs clés</h3>
                    <div class="admin-kpi-list">
                        <div class="admin-kpi-item">
                            <span>Articles publiés ce mois</span>
                            <strong>{{ $stats['published_this_month'] }}</strong>
                        </div>
                        <div class="admin-kpi-item">
                            <span>Articles en brouillon</span>
                            <strong>{{ $stats['draft_articles'] }}</strong>
                        </div>
                        <div class="admin-kpi-item">
                            <span>Vues totales</span>
                            <strong>{{ number_format($stats['total_views'], 0, ',', ' ') }}</strong>
                        </div>
                        <div class="admin-kpi-item">
                            <span>Vues moyennes / article</span>
                            <strong>{{ number_format($stats['avg_views_per_article'], 0, ',', ' ') }}</strong>
                        </div>
                        <div class="admin-kpi-item">
                            <span>Total commentaires</span>
                            <strong>{{ number_format($stats['total_comments'], 0, ',', ' ') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('dashboardChart');
            if (!canvas || typeof Chart === 'undefined') return;

            const labels = @json($chart['labels']);
            const publishedData = @json($chart['published']);
            const viewsData = @json($chart['views']);

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Articles publiés',
                            data: publishedData,
                            borderColor: '#4e6ef2',
                            backgroundColor: 'rgba(78, 110, 242, 0.1)',
                            fill: true,
                            tension: 0.35
                        },
                        {
                            label: 'Vues',
                            data: viewsData,
                            borderColor: '#ff7800',
                            backgroundColor: 'rgba(255, 120, 0, 0.1)',
                            fill: true,
                            tension: 0.35
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
