@php
    $items = $breadcrumbItems ?? [];
@endphp
<section class="inner-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Accueil</a>
                    </li>
                    @foreach ($items as $item)
                        <li class="breadcrumb-item">
                            <a href="{{ $item['url'] }}"><i class="fa-solid fa-chevron-right me-2"></i>{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fa-solid fa-chevron-right me-2"></i><span>{{ $title }}</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>
