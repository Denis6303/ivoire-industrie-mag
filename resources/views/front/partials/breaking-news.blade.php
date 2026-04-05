@if (isset($breakingNews) && $breakingNews->isNotEmpty())
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/owl-carousel/owl.carousel.min.css') }}">
    @endpush

    <section class="breaking-news">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-2 col-lg-3 col-md-4">
                    <span class="news-btn">{{ __('breaking.news_label') }} <i class="fa-solid fa-caret-right"></i></span>
                </div>
                <div class="col-xl-10 col-lg-9 col-md-8">
                    <div
                        class="owl-carousel arrow-styel-03"
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
                                            <img class="img-fluid" src="{{ $img }}" alt="">
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
                <div class="breaking-news-slide"></div>
            </div>
        </div>
    </section>
@endif
