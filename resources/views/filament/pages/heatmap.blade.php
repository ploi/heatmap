<x-filament::page>
    <div class="heatmap w-full h-screen rounded-lg overflow-x-hidden shadow">
        <iframe src="http://heatmap-test.test" class="w-full h-screen bg-white"></iframe>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (e) => {
            var heatmapWidth = document.querySelector('.heatmap').clientWidth;
            var heatmapHeight = document.querySelector('.heatmap').clientHeight;

            let data = JSON.parse('@json($clicks)').map(function(element){
                element.x = (heatmapWidth * element.x).toFixed(0)
                element.y = (heatmapHeight * element.y).toFixed(0)
                return element;
            });

            window.heatmap.setData({data: data})
        });
    </script>
</x-filament::page>


