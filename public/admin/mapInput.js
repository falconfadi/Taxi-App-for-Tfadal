L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

L.CustomHandler = L.Handler.extend({

    includes: L.Evented,

    initialize: function(map) {
        this._map = map;
    },

    addHooks: function() {
        this._map
            .on('mousedown', this._onMouseDown, this)
            .on('mouseup', this._onMouseUp, this)
    },

    removeHooks: function() {
        this._map
            .off('mousedown', this._onMouseDown, this)
            .off('mouseup', this._onMouseUp, this)
    },

    _onMouseDown: function(e) {
        console.log('mousedown');
        e.originalEvent.stopPropagation();
    },

    _onMouseUp: function(e) {
        console.log('mouseup');
        e.originalEvent.stopPropagation();
    }

});

map.addHandler('customHandler', L.CustomHandler);

map.customHandler.enable();

map.on('click', () => console.log('map click'));
