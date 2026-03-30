@php
    // Load the template HTML remotely (hotfix): no dependency on local decompressed folders.
    $sourceUrl = 'https://themes.potenzaglobalsolutions.com/html/nezzy/about-us.html';
    $html = @file_get_contents($sourceUrl);

    if (! is_string($html) || $html === '') {
        return;
    }

    if (! preg_match('/<!--=================================\s*Inner Header\s*-->/s', $html, $startMatch, PREG_OFFSET_CAPTURE)) {
        return;
    }
    if (! preg_match('/<!--=================================\s*Footer\s*-->/s', $html, $endMatch, PREG_OFFSET_CAPTURE)) {
        return;
    }

    $startOffset = $startMatch[0][1];
    $endOffset = $endMatch[0][1];

    $snippet = substr($html, $startOffset, $endOffset - $startOffset);

    // Normaliser les liens et libellés About/Contact (sans suffixe "us")
    $snippet = str_replace(
        ['about-us.html', 'contact-us.html', 'About Us', 'Contact Us', 'Contact us', 'Contact us now', 'Contact us now to lock rate'],
        ['about.html', 'contact.html', 'About', 'Contact', 'Contact', 'Contact now', 'Contact now to lock rate'],
        $snippet
    );

    echo $snippet;
@endphp

