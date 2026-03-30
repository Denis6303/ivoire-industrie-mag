@php
    // Load the template HTML remotely (hotfix): no dependency on local decompressed folders.
    $sourceUrl = 'https://themes.potenzaglobalsolutions.com/html/nezzy/index.html';
    $html = @file_get_contents($sourceUrl);

    if (! is_string($html)) {
        return;
    }

    $startOffset = null;
    $bodyEndOffset = null;

    if (preg_match('/<!--=================================\s*Footer\s*-->/s', $html, $m, PREG_OFFSET_CAPTURE)) {
        $startOffset = $m[0][1];
    }

    // Ne pas inclure les balises de fin </body></html> : le layout les gère.
    $bodyEndOffset = strrpos($html, '</body>');

    $snippet = ($startOffset !== null && $bodyEndOffset !== false)
        ? substr($html, $startOffset, $bodyEndOffset - $startOffset)
        : '';

    echo $snippet;
@endphp

