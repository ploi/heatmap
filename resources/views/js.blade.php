function getWidth() {
    return Math.max(
        document.body.scrollWidth,
        document.documentElement.scrollWidth,
        document.body.offsetWidth,
        document.documentElement.offsetWidth,
        document.documentElement.clientWidth
    );
}

function getHeight() {
    return Math.max(
        document.body.scrollHeight,
        document.documentElement.scrollHeight,
        document.body.offsetHeight,
        document.documentElement.offsetHeight,
        document.documentElement.clientHeight
    );
}

let HEATMAP = {
    debug: false,

    settings: {
        url: '{{ $url }}',
        clicks: Boolean('{{ $clicks }}'),
        clicksThreshold: 10,
        movement: Boolean('{{ $movement }}'),
    },

    data: {
        clicks: [],
        movement: []
    },

    init: () => {
        if (HEATMAP.settings.clicks) {
            HEATMAP.initClicks();
        }

        if (HEATMAP.settings.movement) {
            HEATMAP.trackMovement();
        }
    },

    initClicks: () => {
        // When the user clicks
        addEventListener('click', async (e) => {
            // @TODO: Don't send w/h with each event, do it once
            let data = {
                x: e.pageX,
                y: e.pageY,
                w: getWidth(),
            };

            HEATMAP.data.clicks.push(data);

            console.log(data);

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
            clicks: HEATMAP.data.clicks
        });
    },

    trackMovement: () => {

    },

    send: async (data) => {
        data.width = HEATMAP.windowWidth();
        data.height = HEATMAP.windowHeight();

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

    getScrollLeft: function () {
        return (document.body.scrollLeft || window.pageXOffset || document.documentElement.scrollLeft) | 0;
    },

    getScrollTop: function () {
        return (document.body.scrollTop || window.pageYOffset || document.documentElement.scrollTop) | 0;
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
