(function(factory){
    var id = 'WCtl';
    if (typeof define == 'function' && define.amd) {
        define(id, ['jquery'], factory);
    } else {
        self[id] = factory(jQuery);
    }
})(function($){
    var hasOwn = ({}).hasOwnProperty;
    
    function WCtl(options){
        this.set(options);
    }
    
    WCtl.prototype = {
        _window: null,
        _options: {
            width: '400px',
            height: '500px'
        },
        _stack: null,
        set: function(options)
        {
            if (typeof options == 'object') {
                $.extend(this._options, options);
            }
            
            return this;
        },
        get: function()
        {
            var sets = [],
            options = this._options;
            
            for (var prop in options) {
                if (hasOwn.call(options, prop)) {
                    sets.push(prop + '=' + options[prop]);
                }
            }
            
            return sets.join(',');
        },
        open: function(url, name)
        {
            var sets = [];
            
            name = name || '';
            
            try {
                this._window = window.open(url, name, this.get());
            } catch (e) {
                if (self.console) {
                    console.log(e.stack, url, name, this.get());
                    this._stack = e.stack;
                }
            }

            return this;
        },
        close: function()
        {
            this._window && this._window.close()
        }
    };
    
    hasOwn = ({}).hasOwnProperty;
    
    return WCtl;
});
