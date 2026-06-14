(function () {
    'use strict';

    var root = document.querySelector('[data-article-stats]');
    if (!root) {
        return;
    }

    var endpoint = root.getAttribute('data-endpoint');
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = csrf ? csrf.getAttribute('content') : '';
    var sentScroll = {};
    var qualifiedSent = false;
    var startTime = Date.now();
    var maxScroll = 0;
    var hasInternalNav = false;

    function send(event, payload) {
        if (!endpoint) {
            return;
        }

        var body = JSON.stringify({ event: event, payload: payload || {} });

        if (navigator.sendBeacon) {
            var blob = new Blob([body], { type: 'application/json' });
            var form = new FormData();
            form.append('_token', token);
            form.append('event', event);
            if (payload) {
                Object.keys(payload).forEach(function (key) {
                    form.append('payload[' + key + ']', payload[key]);
                });
            }
            navigator.sendBeacon(endpoint, form);
            return;
        }

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: body,
            keepalive: true,
            credentials: 'same-origin'
        }).catch(function () {});
    }

    function scrollDepth() {
        var doc = document.documentElement;
        var scrollTop = window.pageYOffset || doc.scrollTop;
        var height = doc.scrollHeight - doc.clientHeight;
        if (height <= 0) {
            return 0;
        }
        return Math.min(100, Math.round((scrollTop / height) * 100));
    }

    function maybeTrackScroll() {
        var depth = scrollDepth();
        maxScroll = Math.max(maxScroll, depth);
        [25, 50, 75, 100].forEach(function (mark) {
            if (depth >= mark && !sentScroll[mark]) {
                sentScroll[mark] = true;
                send('scroll_' + mark);
            }
        });

        var elapsed = Math.round((Date.now() - startTime) / 1000);
        if (!qualifiedSent && depth >= 50 && elapsed >= 30) {
            qualifiedSent = true;
            send('qualified_read');
        }
    }

    window.addEventListener('scroll', maybeTrackScroll, { passive: true });
    maybeTrackScroll();

    window.addEventListener('beforeunload', function () {
        var seconds = Math.round((Date.now() - startTime) / 1000);
        if (seconds >= 3) {
            send('time_on_page', { seconds: seconds, scroll: maxScroll });
        }
        if (!hasInternalNav && seconds < 15 && maxScroll < 25) {
            send('bounce');
        }
    });

    document.addEventListener('click', function (event) {
        var target = event.target.closest('a, button, img');
        if (!target) {
            return;
        }

        if (target.closest('[data-share-network]')) {
            send('share_' + target.closest('[data-share-network]').getAttribute('data-share-network'));
            return;
        }

        if (target.matches('.ivm-article-cover-wrap img, .ivm-article-cover-wrap a')) {
            send('click_cover');
            return;
        }

        if (target.matches('.ivm-inline-article-image img')) {
            send('click_secondary');
            return;
        }

        if (target.closest('.bg-white.mt-5 .row.mt-3')) {
            send('click_related');
            hasInternalNav = true;
            return;
        }

        if (target.closest('[data-track-newsletter]')) {
            send('click_newsletter');
            return;
        }

        var link = target.closest('a[href]');
        if (!link) {
            return;
        }

        var href = link.getAttribute('href') || '';
        if (href.indexOf('/emplois') !== -1) {
            send('click_jobs');
            hasInternalNav = true;
            return;
        }
        if (href.indexOf('/entreprises') !== -1) {
            send('click_companies');
            hasInternalNav = true;
            return;
        }

        if (link.hostname && link.hostname !== window.location.hostname) {
            send('click_external', { url: href });
            return;
        }

        if (href.charAt(0) === '/' || href.indexOf(window.location.hostname) !== -1) {
            if (link.closest('.article-body')) {
                send('click_internal', { url: href });
            }
            hasInternalNav = true;
        }
    });

    document.querySelectorAll('form.newsletter-box').forEach(function (form) {
        form.addEventListener('submit', function () {
            send('newsletter_signup');
        });
    });

    if ('PerformanceObserver' in window) {
        try {
            var lcp = null;
            var observer = new PerformanceObserver(function (list) {
                list.getEntries().forEach(function (entry) {
                    if (entry.entryType === 'largest-contentful-paint') {
                        lcp = Math.round(entry.startTime);
                    }
                });
            });
            observer.observe({ type: 'largest-contentful-paint', buffered: true });
            window.addEventListener('load', function () {
                setTimeout(function () {
                    var nav = performance.getEntriesByType('navigation')[0];
                    var payload = {
                        lcp: lcp,
                        cls: 0,
                        inp: nav ? Math.round(nav.domInteractive) : null
                    };
                    send('web_vitals', payload);
                }, 3000);
            });
        } catch (e) {}
    }
})();
