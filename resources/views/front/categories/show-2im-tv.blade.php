@extends('layouts.front')

@section('title', e(category_i18n($category)))
@section('meta_description', e(\Illuminate\Support\Str::limit(strip_tags($category->description ?? __('front.tv_meta_description')), 160)))

@push('styles')
    <style>
        .ivm-yt-hero {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 50%, #1a1a1a 100%);
            color: #fff;
            border-radius: 0.75rem;
            padding: 1.75rem 1.5rem;
        }
        .ivm-yt-hero .ivm-yt-brand {
            color: #ff7800;
        }
        .ivm-yt-thumb-wrap {
            border: 0;
            background: transparent;
            cursor: pointer;
        }
        .ivm-yt-thumb-wrap:focus {
            outline: 2px solid #ff7800;
            outline-offset: 2px;
        }
        .ivm-yt-play-icon {
            font-size: 2.75rem;
            opacity: 0.95;
            color: #ff0000;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,.45));
        }
        .ivm-yt-card .ratio img {
            object-fit: cover;
        }
        .ivm-yt-text-item {
            border-left: 3px solid #ff7800;
            padding-left: 0.75rem;
        }
    </style>
@endpush

@section('content')
    @include('front.partials.page-header', [
        'title' => category_i18n($category),
        'breadcrumbItems' => [['label' => __('front.articles_title'), 'url' => route('articles.index')]],
    ])

    <section class="space-ptb">
        <div class="container">
            <div class="ivm-yt-hero mb-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <p class="small text-uppercase text-white-50 mb-1">{{ __('front.tv_badge') }}</p>
                        <h1 class="h3 mb-2"><span class="ivm-yt-brand"><i class="fa-brands fa-youtube me-2" aria-hidden="true"></i>{{ category_i18n($category) }}</span></h1>
                        <p class="mb-0 text-white-75">{{ __('front.tv_intro') }}</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        @php $ytChannel = config('ivoireindustriemag.youtube.channel_url'); @endphp
                        @if (is_string($ytChannel) && $ytChannel !== '' && $ytChannel !== '#')
                            <a href="{{ $ytChannel }}" class="btn btn-lg btn-danger me-lg-2 mb-2 mb-lg-0" target="_blank" rel="noopener noreferrer">
                                <i class="fa-brands fa-youtube me-2" aria-hidden="true"></i>{{ __('front.tv_subscribe_cta') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if (!empty($playlistEmbedUrl))
                <div class="mb-5">
                    <h2 class="h5 mb-3">{{ __('front.tv_playlist_title') }}</h2>
                    <div class="ratio ratio-16x9 shadow rounded overflow-hidden bg-dark">
                        <iframe
                            src="{{ $playlistEmbedUrl }}"
                            title="YouTube"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                        ></iframe>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <h2 class="h5 mb-3">{{ __('front.tv_video_section') }}</h2>
                    <div class="row g-4">
                        @forelse ($videos as $video)
                            <div class="col-md-6">
                                <div class="ivm-yt-card card border-0 shadow-sm h-100 overflow-hidden">
                                    <button
                                        type="button"
                                        class="ivm-yt-thumb-wrap w-100 text-start"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ivmYtModal"
                                        data-youtube-id="{{ $video->youtube_video_id }}"
                                        aria-label="{{ __('front.tv_play_video', ['title' => $video->title]) }}"
                                    >
                                        <div class="ratio ratio-16x9 bg-dark position-relative">
                                            <img
                                                src="https://i.ytimg.com/vi/{{ $video->youtube_video_id }}/hqdefault.jpg"
                                                alt=""
                                                class="w-100 h-100"
                                                loading="lazy"
                                                decoding="async"
                                            >
                                            <span class="position-absolute top-50 start-50 translate-middle ivm-yt-play-icon" aria-hidden="true">
                                                <i class="fa-brands fa-youtube"></i>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="h6 mb-1">{{ $video->title }}</h3>
                                            @if ($video->description)
                                                <p class="small text-muted mb-1">{{ \Illuminate\Support\Str::limit(strip_tags($video->description), 120) }}</p>
                                            @endif
                                            @if ($video->published_at)
                                                <p class="small text-muted mb-0">{{ $video->published_at->translatedFormat('d M Y') }}</p>
                                            @endif
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">{{ __('front.tv_no_items') }}</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $videos->links() }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        @include('front.partials.sidebar-recent-posts', ['recentArticles' => $recentArticles])
                        @if (!empty($adSidebar))
                            <div class="widget mb-4">
                                @include('front.partials.ad-banner', ['ad' => $adSidebar, 'variant' => 'vertical'])
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="ivmYtModal" tabindex="-1" aria-labelledby="ivmYtModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header border-secondary">
                    <h2 class="modal-title text-white h5" id="ivmYtModalLabel">{{ __('front.tv_modal_title') }}</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <iframe
                            id="ivmYtIframe"
                            src=""
                            title="YouTube video"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            referrerpolicy="strict-origin-when-cross-origin"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalEl = document.getElementById('ivmYtModal');
            var iframe = document.getElementById('ivmYtIframe');
            if (!modalEl || !iframe) {
                return;
            }
            modalEl.addEventListener('show.bs.modal', function (event) {
                var trigger = event.relatedTarget;
                var id = trigger && trigger.getAttribute('data-youtube-id');
                if (id) {
                    iframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
                }
            });
            modalEl.addEventListener('hidden.bs.modal', function () {
                iframe.src = '';
            });
        });
    </script>
@endpush
