@php
    $shareUrls = article_share_urls($article, $articleTitle);
@endphp

<div
    class="ivm-article-share mt-4 pt-4 border-top"
    data-share-page-url="{{ $shareUrls['page'] }}"
    data-share-title="{{ e($articleTitle) }}"
>
    <h6 class="widget-title text-uppercase fw-bolder mb-3">Partager cet article</h6>
    <div class="ivm-share-buttons" role="group" aria-label="Partager cet article">
        <a
            href="{{ $shareUrls['facebook'] }}"
            class="ivm-share-btn ivm-share-btn--facebook"
            target="_blank"
            rel="noopener noreferrer"
            data-share-network="facebook"
            aria-label="Partager sur Facebook"
            title="Facebook"
        >
            <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
        </a>
        <a
            href="{{ $shareUrls['linkedin'] }}"
            class="ivm-share-btn ivm-share-btn--linkedin"
            target="_blank"
            rel="noopener noreferrer"
            data-share-network="linkedin"
            aria-label="Partager sur LinkedIn"
            title="LinkedIn"
        >
            <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
        </a>
        <a
            href="{{ $shareUrls['twitter'] }}"
            class="ivm-share-btn ivm-share-btn--twitter"
            target="_blank"
            rel="noopener noreferrer"
            data-share-network="twitter"
            aria-label="Partager sur X"
            title="X"
        >
            @include('front.partials.icon-social-x', ['size' => 18])
        </a>
        <a
            href="{{ $shareUrls['whatsapp'] }}"
            class="ivm-share-btn ivm-share-btn--whatsapp"
            target="_blank"
            rel="noopener noreferrer"
            data-share-network="whatsapp"
            aria-label="Partager sur WhatsApp"
            title="WhatsApp"
        >
            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
        </a>
        <button
            type="button"
            class="ivm-share-btn ivm-share-btn--copy"
            data-share-network="copy"
            data-share-url="{{ $shareUrls['page'] }}"
            aria-label="Copier le lien de l'article"
            title="Copier le lien"
        >
            <i class="fa-solid fa-link" aria-hidden="true"></i>
        </button>
    </div>
    <p class="ivm-share-feedback small text-success mb-0 mt-2 d-none" data-share-feedback aria-live="polite"></p>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('click', function (event) {
                var link = event.target.closest('.ivm-share-btn[data-share-network]:not([data-share-network="copy"])');
                if (!link || !link.getAttribute('href')) {
                    return;
                }

                event.preventDefault();
                window.open(
                    link.getAttribute('href'),
                    'ivm-share',
                    'noopener,noreferrer,width=640,height=640,scrollbars=yes'
                );
            });
        </script>
    @endpush
@endonce
