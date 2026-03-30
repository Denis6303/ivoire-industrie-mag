@php
    // Load the template HTML remotely (hotfix): no dependency on local decompressed folders.
    $sourceUrl = 'https://themes.potenzaglobalsolutions.com/html/nezzy/index.html';
    $html = @file_get_contents($sourceUrl);

    if (! is_string($html) || $html === '') {
        return;
    }

    if (! preg_match('/<!--=================================\s*Breaking News\s*-->/s', $html, $startMatch, PREG_OFFSET_CAPTURE)) {
        return;
    }
    if (! preg_match('/<!--=================================\s*Footer\s*-->/s', $html, $endMatch, PREG_OFFSET_CAPTURE)) {
        return;
    }

    $startOffset = $startMatch[0][1];
    $endOffset = $endMatch[0][1];

    $snippet = substr($html, $startOffset, $endOffset - $startOffset);

    // Normaliser les liens About/Contact (sans suffixe "us")
    $snippet = str_replace(
        ['about-us.html', 'contact-us.html', 'About Us', 'Contact Us', 'Contact us'],
        ['about.html', 'contact.html', 'About', 'Contact', 'Contact'],
        $snippet
    );

    echo $snippet;
@endphp

