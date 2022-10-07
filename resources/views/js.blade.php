let HEATMAP = {
    settings: {
        debug: Boolean(parseInt('{{ $debug === false ? "0" : "1" }}')),
        url: '{{ $url }}',
        baseUrl: '{{ $baseUrl }}',
        hash: '{{ $hash }}',
        clicks: Boolean(parseInt('{{ $clicks }}')),
        clicksThreshold: 10,
        movement: Boolean(parseInt('{{ $movement }}')),
    },

    data: {
        clicks: [],
        movement: []
    },

    init: () => {
        if (HEATMAP.isLoadedInHeatmap()) {
            HEATMAP.trackIframeScroll();
            HEATMAP.trackIframeNavigation();
        }

        // When we're inside the iframe, we want to track scrolling and navigation. We'll also return from this
        // function to prevent tracking movements and/or clicks from the heatmap software.
        if (HEATMAP.isLoadedInHeatmap() && !HEATMAP.settings.debug) {
            return;
        }

        // Track clicks if enabled
        if (HEATMAP.settings.clicks) {
            HEATMAP.initClicks();
        }

        // Track movement if enabled
        if (HEATMAP.settings.movement) {
            HEATMAP.trackMovement();
        }
    },

    initClicks: () => {
        // When the user clicks
        addEventListener('click', async (e) => {
            let data = {
                x: e.pageX,
                y: e.pageY,
            };

            HEATMAP.data.clicks.push(data);

            if (HEATMAP.data.clicks.length >= HEATMAP.settings.clicksThreshold) {
                await HEATMAP.trackClicks();

                HEATMAP.data.clicks = [];
            }
        });

        // When a user refreshes, or navigates away
        addEventListener('beforeunload', async (e) => {
            await HEATMAP.trackClicks();
        });
    },

    trackClicks: async () => {
        // Don't send any data, if we don't have any
        if (!HEATMAP.data.clicks.length) {
            return;
        }

        await HEATMAP.send({
            clicks: HEATMAP.data.clicks,
            width: HEATMAP.getWidth(),
            height: HEATMAP.getHeight(),
            path: window.location.pathname
        });
    },

    trackMovement: () => {

    },

    trackIframeScroll: () => {
        addEventListener('scroll', (event) => {
            HEATMAP.sendMessage({task: 'scroll', scrollY: window.scrollY})
        });
    },

    trackIframeNavigation: () => {
        addEventListener('load', (event) => {
            HEATMAP.sendMessage({task: 'navigate', url: window.location.href})
        });
    },

    send: async (data) => {
        data.hash = HEATMAP.settings.hash;

        await fetch(HEATMAP.settings.url, {
            method: 'POST',
            keepalive: true,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
            .then((response) => response.json())
    },

    getWidth: () => {
        return Math.max(
            document.body.scrollWidth,
            document.documentElement.scrollWidth,
            document.body.offsetWidth,
            document.documentElement.offsetWidth,
            document.documentElement.clientWidth
        );
    },

    getHeight: () => {
        return Math.max(
            document.body.scrollHeight,
            document.documentElement.scrollHeight,
            document.body.offsetHeight,
            document.documentElement.offsetHeight,
            document.documentElement.clientHeight
        );
    },

    sendMessage: (payload) => {
        window.parent.postMessage(JSON.stringify(payload), '{{ $baseUrl }}');
    },

    isLoadedInHeatmap: () => {
        return window.location.ancestorOrigins[0] === HEATMAP.settings.baseUrl;
    }
};

document.addEventListener('DOMContentLoaded', (e) => {
    HEATMAP.init();
});
