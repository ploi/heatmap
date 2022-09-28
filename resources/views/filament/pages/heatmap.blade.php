<x-filament::page>
    <style>
        #wrapper {
            position: absolute;
            width: 50px;
            height: 50px;
        }

        .heatmap {
            color: #FFFFFF;
            font-size: 26px;
            font-weight: bold;
            text-shadow: -1px -1px 1px #000, 1px 1px 1px #000;
            position: relative;
            z-index: 100;
            height: 1628px;
            width: 1200px;
        }

        .bgiframe {
            color: #999999;
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
    <div id="wrapper">
        <div class="heatmap overlay" id="heatmapContainer">
        </div>
        <div class="bgiframe">
            <iframe src="/test/Document.html" id="iframe" title="iFrame" height="500"
                    width="1200" frameborder="0"></iframe>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setHeatmapData();
        });

        let resizeId;

        window.addEventListener('resize', (e) => {
            clearTimeout(resizeId);
            resizeId = setTimeout(setHeatmapData, 350);
        });

        function setHeatmapData() {
            // create heatmap with configuration
            // now generate some random data
            var max = 10;

            // @TODO: Needs to be width/height of document inside iframe
            var width = 1200;
            var height = 2049;

            let clicks = JSON.parse('@json($clicks)');

            // Do it again so we can see, it's JS so just doing clicks means seeing the changed data... just temp debug
            console.log(JSON.parse('@json($clicks)'));

            let mapped = clicks.map(function (element) {
                var originalScaleWidth = (width - element.w) / 2;
                var originalScaleHeight = (height - element.h) / 2;

                element.x = Math.floor(element.x + originalScaleWidth);
                element.y = Math.floor(element.y + originalScaleHeight);

                return element;
            });

            console.log(mapped);

            // if you have a set of datapoints always use setData instead of addData
            // for data initialization
            window.heatmap.setData({
                max: max,
                data: mapped
            });
        }

        let iframe = document
            .querySelector('#iframe')
        let heatmap = document.getElementById('heatmapContainer')
        iframe.addEventListener('load', e => {
            e.target.contentWindow.addEventListener('scroll', e => {
                let scroll = iframe.contentWindow.document.documentElement.scrollTop;
                heatmap.style.transform = `translateY(${-scroll}px)`;
                // console.log(scroll)
            });
        });
    </script>
</x-filament::page>
