import h337 from '@mars3d/heatmap.js';

if(document.querySelector('.heatmap')){
    window.heatmap = h337.create({
        container: document.querySelector('.heatmap'),
        radius: 30
    });

}
