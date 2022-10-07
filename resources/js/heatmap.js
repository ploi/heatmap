import h337 from '@mars3d/heatmap.js';

window.createHeatmap = function(){
    document.querySelectorAll(".heatmap-canvas").forEach(el => el.remove());

    window.heatmap = h337.create({
        container: document.querySelector('.heatmap'),
        radius: 30,
        visible: true,
        backgroundColor: 'inherit'
    });
}

window.iframeURLChange = function(iframe, callback) {
    // const unloadHandler = function () {
    //     // Timeout needed because the URL changes immediately after
    //     // the `unload` event is dispatched.
    //     setTimeout(function () {
    //         callback(iframe.contentWindow.location.pathname);
    //     }, 0);
    // };
    //
    // function attachUnload() {
    //     // Remove the unloadHandler in case it was already attached.
    //     // Otherwise, the change will be dispatched twice.
    //     iframe.contentWindow.removeEventListener("unload", unloadHandler);
    //     iframe.contentWindow.addEventListener("unload", unloadHandler);
    // }
    //
    // iframe.addEventListener("load", attachUnload);
    // attachUnload();
}
