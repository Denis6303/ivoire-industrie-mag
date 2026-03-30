@php
    // Load the template HTML remotely (hotfix): no dependency on local decompressed folders.
    $sourceUrl = 'https://themes.potenzaglobalsolutions.com/html/nezzy/index.html';
    $html = @file_get_contents($sourceUrl);

    if (! is_string($html)) {
        return;
    }

    // Extract only the common header area (loader + header + side menu + search overlay),
    // stopping right before "Breaking News" (the next block on index.html).
    $startOffset = null;
    $endOffset = null;

    if (preg_match('/<!--=================================\s*Loader\s*-->/s', $html, $m, PREG_OFFSET_CAPTURE)) {
        $startOffset = $m[0][1];
    }
    if (preg_match('/<!--=================================\s*Breaking News\s*-->/s', $html, $m, PREG_OFFSET_CAPTURE)) {
        $endOffset = $m[0][1];
    }

    $snippet = ($startOffset !== null && $endOffset !== null)
        ? substr($html, $startOffset, $endOffset - $startOffset)
        : '';

    // Remove "-us" suffix from URLs + labels for About/Contact.
    $snippet = str_replace(
        ['about-us.html', 'contact-us.html', 'About Us', 'Contact Us'],
        ['about.html', 'contact.html', 'About', 'Contact'],
        $snippet
    );

    echo $snippet;
@endphp

