@if (isset($breakingNews) && $breakingNews->isNotEmpty())
    <link rel="stylesheet" href="{{ asset('css/owl-carousel/owl.carousel.min.css') }}">
    <style>
        .breaking-news {
            padding: 6px 0 !important;
            margin-bottom: 10px !important;
        }
        .breaking-news .news-btn {
            padding: 6px 15px !important;
            font-size: 12px !important;
            line-height: 1.2;
        }
        .breaking-news .news-post .news-image {
            width: 20px !important;
            height: 20px !important;
            flex: 0 0 20px !important;
            margin-right: 8px !important;
        }
        .breaking-news .news-post .news-post-details .news-title {
            font-size: 12px !important;
            line-height: 1.15 !important;
        }
        .breaking-news .row.align-items-center {
            --bs-gutter-y: 0.25rem;
        }
        .breaking-news .owl-carousel .owl-stage-outer {
            padding: 2px 0;
        }
        .breaking-news .breaking-news-carousel-wrap {
            position: relative;
            padding-right: 58px;
        }
        /* Thème Nezzy : .owl-carousel .owl-nav { opacity: 0 } jusqu’au :hover — on force l’affichage permanent */
        .breaking-news .breaking-news-owl .owl-nav {
            position: absolute;
            right: 0;
            top: 110%;
            transform: translateY(-50%);
            display: flex !important;
            flex-direction: row;
            align-items: center;
            gap: 6px;
            margin: 0;
            width: auto;
            opacity: 1 !important;
            visibility: visible !important;
        }
        .breaking-news .breaking-news-owl:hover .owl-nav {
            opacity: 1 !important;
        }
        .breaking-news .breaking-news-owl:hover .owl-nav button.owl-prev,
        .breaking-news .breaking-news-owl:hover .owl-nav button.owl-next {
            left: auto !important;
            right: auto !important;
        }
        .breaking-news .breaking-news-owl .owl-nav button.owl-prev,
        .breaking-news .breaking-news-owl .owl-nav button.owl-next {
            position: static !important;
            left: auto !important;
            right: auto !important;
            top: auto !important;
            width: 26px;
            height: 26px;
            margin: 0 !important;
            border-radius: 4px;
            background: rgba(255, 120, 0, 0.2) !important;
            color: #ff7800 !important;
            font-size: 11px;
            line-height: 1;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .breaking-news .breaking-news-owl .owl-nav button.owl-prev:hover,
        .breaking-news .breaking-news-owl .owl-nav button.owl-next:hover,
        .breaking-news .breaking-news-owl .owl-nav button.owl-prev:focus,
        .breaking-news .breaking-news-owl .owl-nav button.owl-next:focus {
            background: #ff7800 !important;
            color: #fff !important;
            outline: none;
        }
        .breaking-news .breaking-news-owl .owl-nav button.owl-prev span,
        .breaking-news .breaking-news-owl .owl-nav button.owl-next span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        /* Flèches toujours visibles (Owl masque ou désactive sinon) */
        .breaking-news .breaking-news-owl .owl-nav button.disabled {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 0.45 !important;
            pointer-events: none;
        }
        @media (max-width: 767px) {
            .breaking-news .breaking-news-carousel-wrap {
                padding-right: 52px;
            }
            .breaking-news .breaking-news-owl .owl-nav button.owl-prev,
            .breaking-news .breaking-news-owl .owl-nav button.owl-next {
                width: 24px;
                height: 24px;
            }
        }
        section.breaking-news ~ section.space-ptb {
            padding-top: 22px !important;
        }
        @media (max-width: 767px) {
            .breaking-news .news-btn {
                margin-bottom: 6px !important;
            }
            section.breaking-news ~ section.space-ptb {
                padding-top: 18px !important;
            }
        }
    </style>

    <section class="breaking-news">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <span class="news-btn">{{ __('breaking.news_label') }} <i class="fa-solid fa-caret-right"></i></span>
                </div>
                <div class="col-xl-10 col-lg-9 col-md-8">
                    <div class="breaking-news-carousel-wrap">
                    <div
                        class="owl-carousel breaking-news-owl"
                        data-nav-dots="false"
                        data-nav-arrow="true"
                        data-items="4"
                        data-xl-items="4"
                        data-lg-items="3"
                        data-md-items="3"
                        data-sm-items="2"
                        data-xs-items="2"
                        data-xx-items="1"
                        data-autoheight="true"
                    >
                        @foreach ($breakingNews as $article)
                            <div class="item">
                                <div class="news-post">
                                    <div class="news-image">
                                        @php $img = article_cover($article->cover_image); @endphp
                                        @if ($img)
                                            <img class="w-100 h-100" style="object-fit: cover;" src="{{ $img }}" alt="" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-square.svg') }}';">
                                        @else
                                            <span class="d-block w-100 h-100 bg-secondary"></span>
                                        @endif
                                    </div>
                                    <div class="news-post-details">
                                        <h6 class="news-title">
                                            <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    </div>
                </div>
                <div class="breaking-news-slide"></div>
            </div>
        </div>
    </section>
@endif
