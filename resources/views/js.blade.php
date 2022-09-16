let HEATMAP = {
    debug: false,

    settings: {
        url: '{{ $url }}',
        clicks: Boolean('{{ $clicks }}'),
        clicksThreshold: 2,
        movement: Boolean('{{ $movement }}'),
    },

    data: {
        clicks: [],
        movement: []
    },

    init: () => {
        if (HEATMAP.settings.clicks) {
            HEATMAP.trackClicks();
        }

        if (HEATMAP.settings.movement) {
            HEATMAP.trackMovement();
        }
    },

    trackClicks: () => {
        addEventListener('click', (e) => {
            HEATMAP.data.clicks.push({
                x: parseFloat((e.clientX / HEATMAP.windowWidth()).toFixed(6)),
                y: parseFloat((e.clientY / HEATMAP.windowHeight()).toFixed(6)),
            })
            
            if (HEATMAP.data.clicks.length >= HEATMAP.settings.clicksThreshold) {
                HEATMAP.send({
                    clicks: HEATMAP.data.clicks
                });

                HEATMAP.data.clicks = [];
            }
        });
    },

    trackMovement: () => {

    },

    send: (data) => {
        fetch(HEATMAP.settings.url, {
            method: 'POST',
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

