<!DOCTYPE html>

<head>

</head>
<body>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$key?>"></script>
<script>
    var geocoder;
    var map;
    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var locations = [
        ['Manly Beach', 33.52574386128534, 36.318072848768885, 1],
        ['Bondi Beach', 33.521884993298244, 36.315623530149544, 2],
        ['Coogee Beach', 33.519950574981344, 36.31112367642512, 3]
        /*['Maroubra Beach',33.51784204737573, 36.316653498405536, 4],
        ['Cronulla Beach', 33.5158528748027, 36.31982864777078, 5]*/
    ];

    function initialize() {
        directionsDisplay = new google.maps.DirectionsRenderer();


        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: new google.maps.LatLng(-33.92, 151.25),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        directionsDisplay.setMap(map);
        var infowindow = new google.maps.InfoWindow();

        var marker, i;
        var request = {
            travelMode: google.maps.TravelMode.DRIVING
        };
        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            })(marker, i));

            if (i == 0) request.origin = marker.getPosition();
            else if (i == locations.length - 1) request.destination = marker.getPosition();
            else {
                if (!request.waypoints) request.waypoints = [];
                request.waypoints.push({
                    location: marker.getPosition(),
                    stopover: true
                });
            }

        }
        directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
            }
        });
    }
    google.maps.event.addDomListener(window, "load", initialize);
</script>
<style>
    html,
    body,
    #map {
        height: 100%;
        width: 100%;
        margin: 0px;
        padding: 0px
    }
</style>
<div id="map"></div>
</body>
</html>
