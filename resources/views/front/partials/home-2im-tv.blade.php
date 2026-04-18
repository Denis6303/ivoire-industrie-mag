{{--
    Dernière zone avant le footer : bloc 2IM TV (vidéos enregistrées dans l’admin).
--}}
@php
    $tvRoute = route('categories.show', ['slug' => '2im-tv']);
@endphp
<section class="space-ptb" style="background: linear-gradient(180deg, #f8fafc 0%, #fff 100%); border-top: 1px solid #eef1f6;">
    <div class="container">
        <div class="section-title mb-4 d-flex flex-wrap align-items-end justify-content-between gap-2">
            <div>
                <h2 class="mb-1 d-flex align-items-center ivm-section-title">
                    <span class="ivm-section-dot me-2" style="background:#dc3545;"></span>
                    <span class="text-dark">{{ __('nav.tv') }}</span>
                </h2>
                <p class="text-muted small mb-0">{{ __('front.home_tv_intro') }}</p>
            </div>
            <a href="{{ $tvRoute }}" class="btn btn-primary btn-sm">{{ __('front.home_tv_see_all') }}</a>
        </div>

        <div class="row g-4">
            @foreach ($tvVideos as $video)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden ivm-home-tv-card">
                        <button
                            type="button"
                            class="btn btn-link p-0 text-start w-100 text-decoration-none"
                            data-bs-toggle="modal"
                            data-bs-target="#ivmHomeYtModal"
                            data-youtube-id="{{ $video->youtube_video_id }}"
                            aria-label="{{ __('front.tv_play_video', ['title' => $video->title]) }}"
                        >
                            <div class="ratio ratio-16x9 bg-dark position-relative">
                                <img
                                    src="https://i.ytimg.com/vi/{{ $video->youtube_video_id }}/hqdefault.jpg"
                                    alt=""
                                    class="w-100 h-100"
                                    style="object-fit:cover;"
                                    loading="lazy"
                                >
                                <span class="position-absolute top-50 start-50 translate-middle" style="font-size:2rem;color:#ff0000;" aria-hidden="true">
                                    <i class="fa-brands fa-youtube"></i>
                                </span>
                            </div>
                            <div class="card-body p-2">
                                <p class="small fw-semibold mb-0 text-dark" style="line-height:1.25;">{{ \Illuminate\Support\Str::limit($video->title, 64) }}</p>
                            </div>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="modal fade" id="ivmHomeYtModal" tabindex="-1" aria-labelledby="ivmHomeYtModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-secondary">
                <h2 class="modal-title text-white h5 mb-0" id="ivmHomeYtModalLabel">{{ __('front.tv_modal_title') }}</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe
                        id="ivmHomeYtIframe"
                        src=""
                        title="YouTube"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalEl = document.getElementById('ivmHomeYtModal');
            var iframe = document.getElementById('ivmHomeYtIframe');
            if (!modalEl || !iframe) return;
            modalEl.addEventListener('show.bs.modal', function (event) {
                var t = event.relatedTarget;
                var id = t && t.getAttribute('data-youtube-id');
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
