import h337 from '@mars3d/heatmap.js';

if(document.querySelector('.heatmap')){
    window.heatmap = h337.create({
        container: document.querySelector('.heatmap'),
        // opacity: .2,
        radius: 30,
        visible: true,
        backgroundColor: 'inherit'
    });
}
