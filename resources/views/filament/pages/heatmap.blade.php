<x-filament::page>
    <style>
        .heatmap {
            height: 2500px;
            width: {{ $frameWidth }}px;
            max-width: 100%;
        }
    </style>

    <x-filament::button wire:click="changeSize('smAndLower')">< SM ({{ $sizeCounts['smAndLower'] }})
    </x-filament::button>
    <x-filament::button wire:click="changeSize('smAndMd')">SM >< MD ({{ $sizeCounts['smAndMd'] }})</x-filament::button>
    <x-filament::button wire:click="changeSize('mdAndLg')">MD >< LG ({{ $sizeCounts['mdAndLg'] }})</x-filament::button>
    <x-filament::button wire:click="changeSize('lgAndXl')">LG >< XL ({{ $sizeCounts['lgAndXl'] }})</x-filament::button>
    <x-filament::button wire:click="changeSize('xlAndXxl')">XL >< XXL ({{ $sizeCounts['xlAndXxl'] }})
    </x-filament::button>
    <x-filament::button wire:click="changeSize('xxlAndHigher')">XXL > ({{ $sizeCounts['xxlAndHigher'] }})
    </x-filament::button>

    <div class="bg-white rounded-lg shadow-xl overflow-hidden absolute max-w-full">
        <div class="heatmap overlay pointer-events-none overflow-visible bg-none relative z-[100] max-w-full" id="heatmapContainer">
        </div>
        <div class="h-auto w-auto absolute top-0 left-0 z-0">
            <iframe src="{{ $this->getFullUrl() }}" class="max-w-full" id="heatmapIframe" title="iFrame" height="2500"
                    width="{{ $frameWidth }}" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setHeatmapData();

            window.iframeURLChange(document.getElementById("heatmapIframe"), function (newURL) {
                window.Livewire.emit('urlChanged', newURL)
            });

            window.Livewire.on('heatmapNeedsRendering', () => {
                setHeatmapData();
            })

        });

        function setHeatmapData() {
            window.createHeatmap();

            window.heatmap.setData({
                max: 10,
                data: @this.clicks
            });
        }

        let iframe = document.getElementById('heatmapIframe')
        let heatmap = document.getElementById('heatmapContainer')
        window.addEventListener('message', e => {
            let event = JSON.parse(e.data);
            if (event.task === 'scroll') {
                heatmap.style.transform = `translateY(${-event.scrollY}px)`;
            }
        });
    </script>
</x-filament::page>
