<x-filament::page>
    <style>
        #wrapper {
            position: absolute;
        }

        .heatmap {
            color: #FFFFFF;
            font-size: 26px;
            font-weight: bold;
            text-shadow: -1px -1px 1px #000, 1px 1px 1px #000;
            position: relative;
            z-index: 100;
            height: 100vw;
            width: 1200px;
        }

        .bgiframe {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            height: auto;
            width: auto;
        }

        .overlay {
            overflow: visible;
            pointer-events: none;
            background: none !important;
        }
    </style>

    <x-filament::button>SM >< MD</x-filament::button>
    <x-filament::button>MD >< LG</x-filament::button>
    <x-filament::button disabled>LG >< XL</x-filament::button>
    <x-filament::button>XL ></x-filament::button>

    <div id="wrapper" class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="heatmap overlay" id="heatmapContainer">
        </div>
        <div class="bgiframe">
            <iframe src="{{ $url }}" id="iframe" title="iFrame" height="1000" width="1200" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setHeatmapData();

            iframeURLChange(document.getElementById("iframe"), function (newURL) {
                window.Livewire.emit('urlChanged', newURL)

                setTimeout(() => {
                    setHeatmapData()
                }, 500)
            });
        });

        let resizeId;

        // window.addEventListener('resize', (e) => {
        //     clearTimeout(resizeId);
        //     resizeId = setTimeout(setHeatmapData, 350);
        // });

        function setHeatmapData() {
            window.createHeatmap();

            window.heatmap.setData({
                max: 10,
                data: @this.clicks
            });
        }

        let iframe = document.querySelector('#iframe')
        let heatmap = document.getElementById('heatmapContainer')
        iframe.addEventListener('load', e => {
            e.target.contentWindow.addEventListener('scroll', e => {
                let scroll = iframe.contentWindow.document.documentElement.scrollTop;
                heatmap.style.transform = `translateY(${-scroll}px)`;
            });
        });

        function iframeURLChange(iframe, callback) {
            var unloadHandler = function () {
                // Timeout needed because the URL changes immediately after
                // the `unload` event is dispatched.
                setTimeout(function () {
                    callback(iframe.contentWindow.location.pathname);
                }, 0);
            };

            function attachUnload() {
                // Remove the unloadHandler in case it was already attached.
                // Otherwise, the change will be dispatched twice.
                iframe.contentWindow.removeEventListener("unload", unloadHandler);
                iframe.contentWindow.addEventListener("unload", unloadHandler);
            }

            iframe.addEventListener("load", attachUnload);
            attachUnload();
        }


    </script>
</x-filament::page>
