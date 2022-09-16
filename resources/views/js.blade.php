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
    },

    trackClicks: () => {
        addEventListener('click', (e) => {
            if (HEATMAP.data.clicks.length > HEATMAP.settings.clicksThreshold) {
                HEATMAP.send({
                    clicks: HEATMAP.data.clicks
                });

                HEATMAP.data.clicks = [];
            }

            HEATMAP.data.clicks.push({
                x: e.clientX,
                y: e.clientY
            })
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
    }
};

document.addEventListener('DOMContentLoaded', (e) => {
    HEATMAP.init();
});

