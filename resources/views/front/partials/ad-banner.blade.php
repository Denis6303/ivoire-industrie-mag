@props([
    'ad' => null,
    'variant' => 'horizontal', // horizontal | vertical
])

@if ($ad && !empty($ad->image_url))
    @php
        $image = $ad->image_url;
        if (preg_match('/^https?:\/\//i', $image)) {
            $parts = parse_url($image);
            $path = $parts['path'] ?? '';
            if (str_starts_with($path, '/storage/')) {
                $image = $path;
            }
        } else {
            $image = '/'.ltrim($image, '/');
        }
        $href = !empty($ad->link_url) ? route('ads.click', ['ad' => $ad->id]) : '#';
    @endphp
    <div class="ivm-ad ivm-ad-{{ $variant }}">
        <a href="{{ $href }}" @if (!empty($ad->link_url)) target="_blank" rel="noopener noreferrer" @endif aria-label="{{ $ad->title }}">
            <img src="{{ $image }}" alt="{{ $ad->title }}">
        </a>
    </div>
@endif
