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
            var width = 1200;
            var height = 1628;

            let awd = JSON.parse('@json($clicks)').map(function (element) {
                // element.x = Math.floor(element.x * width)
                // console.log(element.x);
                // console.log(width - 1575);
                element.x = Math.floor(element.x);
                // element.y = Math.floor(element.y * 1628);
                return element;
            });

            console.log(awd);

            // if you have a set of datapoints always use setData instead of addData
            // for data initialization
            window.heatmap.setData({
                max: max,
                data: awd
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


