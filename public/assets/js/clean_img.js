const CLEANUP_ENDPOINT = '/index.php?=ruta=43/clean_img';

document.addEventListener('visibilitychange', function(e){
    if (document.visibilityState === 'hidden'){
        this.navigator.sendBeacon(CLEANUP_ENDPOINT, 'discard_by_close');    
    }
   
});