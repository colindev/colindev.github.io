(function(factory){
    var id = 'Batch';
    if (typeof define == 'function' && define.amd) {
        define(id, [], factory);
    } else {
        self[id] = factory();
    }
})(function(){
    function Batch(duration)
    {
        duration = parseInt(duration, 10);
        
        if ( ! isNaN(duration)) {
            this.duration = duration;
        }
    }
    
    Batch.prototype = {
        duration: 2000,
        run: function(action, times, callback)
        {
            var timer;

            times = parseInt(times, 10) || 0;

            if (0 < times && typeof action == 'function') {
                var des = this.duration / times,
                    cnt = 0;

                timer = setInterval(function(){
                    if (cnt >= times || false === action(cnt)) {
                        timer = clearInterval(timer);
                        typeof callback == 'function' && callback(cnt);
                    }
                    ++cnt;
                }, des);
            }
        }
    };
    
    return Batch;
});