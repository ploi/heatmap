<x-filament::page>
    <div class="heatmap w-full h-screen rounded-lg overflow-x-hidden shadow">
        <iframe src="http://heatmap-test.test" class="w-full h-screen bg-white"></iframe>
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
            let heatmapWidth = document.querySelector('.heatmap').clientWidth;
            let heatmapHeight = document.querySelector('.heatmap').clientHeight;

            let data = JSON.parse('@json($clicks)').map(function (element) {
                element.x = parseInt((heatmapWidth * element.x).toFixed(0))
                element.y = parseInt((heatmapHeight * element.y).toFixed(0))
                return element;
            });

            window.heatmap.setData({data: data})
        }
    </script>
</x-filament::page>


