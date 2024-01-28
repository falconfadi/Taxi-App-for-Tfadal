@extends('layouts/admin')
@section('content')

<div class="content-body">
    <section class="app-user-view">



    <div class="row">
        <!-- User Card starts-->
        <div class="col-xl-12 col-lg-8 col-md-7">
            <div class="card user-card">
                <div class="card-body">
                    <div class="row" style="height: 500px">
                        <style>

                            #map2 {
                                height: 100%;
                                width: 100%;
                                margin: 0px;
                                padding: 0px
                            }
                        </style>

                        <script async defer
                                src="https://maps.googleapis.com/maps/api/js?key=<?=$key?>&callback=initMap">
                        </script>
                        <script>

                            function initMap() {
                                var map = new google.maps.Map(document.getElementById('map2'), {
                                    zoom: 10,
                                    center: {lat: 33.520556, lng: 36.296049}
                                });

                                setMarkers(map);
                            }
                            var locations = <?php echo json_encode($driversCoordinatesArray); ?>

                            console.log(locations);
                            var image = '<?php echo url('storage/car-svgrepo-com.svg'); ?>';
                            function setMarkers(map) {
                                for (var i = 0; i < locations.length; i++) {
                                    var beach = locations[i];
                                    var marker = new google.maps.Marker({
                                        position: {lat: beach[1], lng: beach[2]},
                                        map: map,
                                        title: beach[0],
                                        zIndex: beach[3],
                                        // icon: {
                                        //     path: image,
                                        //     scale: 10,
                                        //     fillColor: 'Crimson',
                                        //     strokeColor: 'Crimson'
                                        // }

                                    });
                                    // const svgMarker = {
                                    //     path: image,
                                    //     fillColor: "blue",
                                    //     fillOpacity: 0.6,
                                    //     strokeWeight: 0,
                                    //     rotation: 0,
                                    //     scale: 2,
                                    //     anchor: new google.maps.Point(0, 20),
                                    // };
                                    //
                                    // new google.maps.Marker({
                                    //     position: map.getCenter(),
                                    //     icon: svgMarker,
                                    //     map: map,
                                    // });
                                }
                            }
                        </script>

                        <div class="col-xl-12 col-lg-12 mt-2 mt-xl-0">
                         @if(!empty($driversCoordinatesArray))   <div id="map2" ></div> @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /User Card Ends-->
    </div>



  <!-- User Timeline & Permissions Starts -->

</section>
        </div>


@endsection
@push('view-page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/pages/app-user.min.css')}}">
@endpush
