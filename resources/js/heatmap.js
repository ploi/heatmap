import h337 from '@mars3d/heatmap.js';

var heatmapWidth = document.querySelector('.heatmap').clientWidth;
var heatmapHeight = document.querySelector('.heatmap').clientHeight;

window.heatmap = h337.create({
    container: document.querySelector('.heatmap'),
    radius: 30
});
