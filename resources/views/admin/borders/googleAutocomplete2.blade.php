<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Leaflet</title>
{{--    <link rel="stylesheet" href="style.css" />--}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet-src.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-size: inherit;
        }
        /** Setting the default font sizes */
        html {
            width: 100%;
            height: 100%;
            background-color: #555566;
        }
        body {
            font-size: 40px;
            width: 100%;
            height: 100%;
            cursor: auto;
            background-color: #555566;
        }
        #map {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            top: 0;
            z-index: 0;
        }
    </style>
</head>

<body>
<div id='map'></div>
<script>
    var map = L.map("map").setView([33.52632232275423, 36.28177404515462], 8);

    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var data = [{id:1,lat:33.52632232275423,lon: 36.28177404515462},
        {id:2,lat:33.520876725188764,lon: 36.239158020863826},
        {id:3,lat:33.51029336401875,lon: 36.358678343320996},
        {id:4,lat:33.50628566028286,lon: 36.29842519899867},

    ];

    for (var p of data) {
        var lat = p.lat;
        var lon = p.lon;
        var markerLocation = new L.LatLng(lat, lon);
        var marker = new L.Marker(markerLocation,{
            draggable: 'true',
            id: p.id,

        }).bindTooltip(p.id.toString(), { permanent: true });
        map.addLayer(marker);

        marker.on('dragend', function (e) {
            // Get position of dropped marker
            var latLng = e.target.getLatLng();
            console.log ("id:"+e.target.options.id);
            console.log ("NewLocation:"+latLng);

        });
    }

</script>
</body>


</html>

<script>

    // create a map in the "map" div, set the view to a given place and zoom

    //const map = L.map("map").setView([39.50, -98.35], 5);

    // add an OpenStreetMap tile layer
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
</script>



