const CLEANUP_ENDPOINT = '/index.php?url=api/delimg';

document.addEventListener('visibilitychange', function(e){
    if (document.visibilityState === 'hidden'){
        this.navigator.sendBeacon(CLEANUP_ENDPOINT, 'discard_by_close');    
    }
   
});