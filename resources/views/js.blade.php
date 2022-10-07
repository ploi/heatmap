let HEATMAP = {
    settings: {
        debug: Boolean(parseInt('{{ $debug === false ? "0" : "1" }}')),
        url: '{{ $url }}',
        baseUrl: '{{ $baseUrl }}',
        hash: '{{ $hash }}',
        clicks: Boolean('{{ $clicks }}'),
        clicksThreshold: 10,
        movement: Boolean('{{ $movement }}'),
    },

    data: {
        clicks: [],
        movement: []
    },

    init: () => {
        // Don't record the iframe inside the heatmap software unless debug mode is on
        if (window.location.ancestorOrigins[0] === HEATMAP.settings.baseUrl && !HEATMAP.settings.debug) {
            return;
        }

        // Track scrolling (so the heatmap iframe scrolls along)
        HEATMAP.trackScroll();
        HEATMAP.trackNavigation();

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

    trackScroll: () => {
        addEventListener('scroll', (event) => {
            window.parent.postMessage(JSON.stringify({ task: 'scroll', scrollY: window.scrollY }), HEATMAP.settings.baseUrl);
        });
    },

    trackNavigation: () => {
        addEventListener('beforeunload', (event) => {
            // window.parent.postMessage(JSON.stringify({ task: 'navigate', scrollY: window.scrollY }), '{{ $baseUrl }}');
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

    getWidth: function () {
        return Math.max(
            document.body.scrollWidth,
            document.documentElement.scrollWidth,
            document.body.offsetWidth,
            document.documentElement.offsetWidth,
            document.documentElement.clientWidth
        );
    },

    getHeight: function () {
        return Math.max(
            document.body.scrollHeight,
            document.documentElement.scrollHeight,
            document.body.offsetHeight,
            document.documentElement.offsetHeight,
            document.documentElement.clientHeight
        );
    },

    windowWidth: function () {
        return Math.max(document.documentElement.clientWidth, window.innerWidth || 0) | 0;
    },

    windowHeight: function () {
        return (window.innerHeight || document.documentElement.clientHeight) | 0;
    },
};

document.addEventListener('DOMContentLoaded', (e) => {
    HEATMAP.init();
});
