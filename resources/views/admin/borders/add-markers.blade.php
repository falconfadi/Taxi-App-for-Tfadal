@extends('layouts/admin')
@push('datatableheader')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush
@section('content')
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
            /*font-size: 40px;
            width: 100%;
            height: 100%;
            cursor: auto;
            background-color: #555566;*/
            cursor: auto;
        }
        #map {
            position: inherit;

            height: 500px;
            z-index: 0;
        }
    </style>


    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{__('page.add-area')}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/borders/store')}}" method="post" >
                @csrf
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('alert-success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                {!! session('alert-success') !!}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$title}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="model">{{__('page.Name')}}</label>
                                        <input type="text" class="form-control" name="name" id="name" value="" required>
                                    </div>
                                </div>
                                <input type="hidden" name="virtualAreaName" value="{{$newArea}}" >
                                <input type="hidden" name="newArea" id="newArea" value="{{$newArea}}" >
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <h3 for="model">يرجى ترتيب النقاط بحسب الارقام</h3>
                                    <div id='map'></div>
                                    <script>
                                        var map = L.map("map").setView([33.52632232275423, 36.28177404515462], 8);

                                        // add an OpenStreetMap tile layer
                                        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                                        var data = [{id:1,lat:33.52632232275423,lon: 36.28177404515462},
                                            {id:2,lat:33.520876725188764,lon: 36.239158020863826},
                                            {id:3,lat:33.51029336401875,lon: 36.358678343320996},
                                            {id:4,lat:33.50628566028286,lon: 36.29842519899867},
                                            {id:5,lat:33.50628566028286,lon: 36.29842519899867},
                                            {id:6,lat:33.50628566028286,lon: 36.29842519899867},
                                            {id:7,lat:33.77728566028286,lon: 36.77742519899867},
                                            {id:8,lat:33.44428566028286,lon: 36.33342519899867},
                                            {id:9,lat:33.50628566028286,lon: 36.29842519899867},
                                            {id:10,lat:33.53338566028286,lon: 36.55519899867},

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
                                                area = document.getElementById("newArea").value
                                                ajaxInsert(e.target.options.id,"."+latLng,area);
                                            });
                                        }
                                    </script>
                                    <script>
                                        function ajaxInsert(id , latLng , area){
                                            $.ajaxSetup({
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                }
                                            });
                                            var url = '{{ url('store-markers') }}';
                                            $.ajax({
                                                url:url,
                                                method:'POST',
                                                data:{
                                                    id:id,
                                                    latLng:latLng,
                                                    area:area
                                                },
                                                success:function(response){
                                                    if(response.success){
                                                        //alert(response.message.php) //Message come from controller
                                                    }else{
                                                        //alert("Error")
                                                    }
                                                },
                                                error:function(error){
                                                    console.log(error)
                                                }
                                            });
                                        }
                                    </script>
                                </div>

                                <div class="col-sm-9 ">
                                    <a type="button" class="btn btn-info mr-1 mb-1 mt-1" href="{{url()->previous() }}">{{__('menus.back')}} </a>
                                    <button type="submit" class="btn btn-primary mr-1 mt-1 mb-1">{{__('page.Submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </section>
        <!-- Basic Inputs end -->
    </div>

    <script src="{{asset('admin/mapInput.js')}}"></script>

@endsection
