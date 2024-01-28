@extends('layouts.admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush
@section('content')
<style>
    #map-canvas,#map_canvas_2 {
        height: 100%;
        margin: 0;
    }
    #map_canvas .centerMarker, #map_canvas_2 .centerMarker{
        position: absolute;
        /*url of the marker*/
        background: url(https://maps.gstatic.com/mapfiles/markers2/marker.png) no-repeat;
        /*center the marker*/
        top: 50%;
        left: 50%;
        z-index: 1;
        /*fix offset when needed*/
        margin-left: -10px;
        margin-top: -34px;
        /*size of the image*/
        height: 34px;
        width: 20px;
        cursor: pointer;
    }
    .mb5{
        margin-bottom:5px
    }
</style>
    <div class="content-header row" id="top">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0" >{{$title}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('company/add_trip')}}" method="post" onsubmit="return checkValidations()" id="add_trip">
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
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errormsgdiv" style="display: none">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('label.add_trip')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="model">{{__('page.Trip_Started')}}</label>
                                        <input type="datetime-local"  class="form-control" name="trip_date" id="trip_date"  />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="car_type_id">{{__('page.Car_Type')}}</label>
                                        <select class="custom-select form-control-border" name="car_type_id" id="car_type_id" required>
                                            @foreach($carTypes as $carType)
                                                <option value="{{$carType->id}}">{{$carType->name_ar}}  </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="users" class="form-label">{{__('label.add_employees_to_trip')}}</label>
                                        <select name="users[]" class="select2 form-control" multiple  id="users">
                                            @foreach($employees as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <label for="" class="col-xl-12 col-md-12 font-16"> الرجاء تحريك الخارطة</label>
                                <div class="col-xl-6 col-md-12 col-12 mb5">
                                    <label for="latitude_from">{{__('label.start_trip')}} </label>
                                    <input type="text" class="form-control " name="latitude_from" id="latitude_from"  readonly>
                                </div>
                                <div class="col-xl-6 col-md-12 col-12 mb5">
                                    <label for="longitude_from">{{__('label.start_trip')}}</label>
                                    <input type="text" class="form-control "  name="longitude_from" id="longitude_from"  readonly>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12">
                                    <div id="map_canvas" class="map col-xl-12 col-md-12" style="height: 350px"></div>
                                </div>
                                <label for="" class="col-xl-12 col-md-12 font-16 mt-1"> الرجاء تحريك الخارطة</label>
                                <div class="col-xl-6 col-md-12 col-12 mb5 ">
                                    <label for="latitude_to">{{__('label.target_trip')}}</label>
                                    <input type="text" class="form-control " name="latitude_to" id="latitude_to"  readonly>
                                </div>
                                <div class="col-xl-6 col-md-12 col-12 mb5">
                                    <label for="longitude_to">{{__('label.target_trip')}}</label>
                                    <input type="text" class="form-control "  name="longitude_to" id="longitude_to"  readonly>
                                </div>

                                <div class="col-xl-12 col-md-12 col-12">
                                    <div id="map_canvas_2" class="map col-xl-12 col-md-12" style="height: 350px"></div>
                                </div>

                                <div class="col-sm-9 mt-2">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('page.Submit')}}</button>
                                    <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url('company/trips')}}">{{__('menus.back')}} </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">

                    </div>
                </div>
            </div>
            </form>
        </section>
        <!-- Basic Inputs end -->
    </div>
@endsection
@push('datatablefooter')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{$key}}"></script>

    <script type="text/javascript">
        function initialize() {
            var mapOptions = {
                zoom: 11,
                center: new google.maps.LatLng(33.515813,36.297226),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById('map_canvas'),
                mapOptions);
            google.maps.event.addListener(map,'center_changed', function() {
                document.getElementById('latitude_from').value = map.getCenter().lat();
                document.getElementById('longitude_from').value = map.getCenter().lng();
            });
            $('<div/>').addClass('centerMarker').appendTo(map.getDiv())
                //do something onclick
                .click(function() {
                    var that = $(this);
                    if (!that.data('win')) {
                        that.data('win', new google.maps.InfoWindow({
                            content: 'this is the center'
                        }));
                        that.data('win').bindTo('position', map, 'center');
                    }
                    that.data('win').open(map);
                });
        }
    </script>
    <script>
        //google.maps.event.addDomListener(window, 'load', initialize);
        window.addEventListener('load', initialize);

        var showMap = $('#show-map');
        function initialize2() {
            var mapOptions = {
                zoom: 11,
                center: new google.maps.LatLng(33.5158131,36.2972261),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map2 = new google.maps.Map(document.getElementById('map_canvas_2'),
                mapOptions);
            google.maps.event.addListener(map2,'center_changed', function() {
                document.getElementById('latitude_to').value = map2.getCenter().lat();
                document.getElementById('longitude_to').value = map2.getCenter().lng();
            });
            $('<div/>').addClass('centerMarker').appendTo(map2.getDiv())
                //do something onclick
                .click(function() {
                    var that = $(this);
                    if (!that.data('win')) {
                        that.data('win', new google.maps.InfoWindow({
                            content: 'this is the center'
                        }));
                        that.data('win').bindTo('position', map2, 'center');
                    }
                    that.data('win').open(map2);
                });
        }

        //google.maps.event.addDomListener(window, 'load', initialize2);
        window.addEventListener('load', initialize2);

        // $(document).ready(function(){
        //     $('#show-map').on('click',initialize2)
        // });
    </script>
@endpush
@push('select2')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('admin/app-assets/js/scripts/forms/form-select2.min.js')}}"></script>
    <!-- END: Page JS-->
@endpush

@push('form_validation')
    <script type="text/javascript">
        function checkValidations()
        {
            //alert(document.getElementById('starttime').value);
            var x = true;
            trip_date = document.getElementById('trip_date').value;
            latitude_from = document.getElementById('latitude_from').value;
            users = document.getElementById('users').value;

            errormsgdiv = document.getElementById('errormsgdiv');
            errormsgdiv.innerHTML = "";

            if( !trip_date || latitude_from == '' || users.length==0)
            {
                errormsgdiv.style.display = "block";
                errormsgdiv.innerHTML='<div class="alert-body"> جميع الحقول المطلوبة </div>';
                x = false;


            }
            var now = new Date();
            var min15 = 1000*60*15;
            if(now.getTime()+min15 >= Date.parse(trip_date)){
                errormsgdiv.style.display = "block";
                errormsgdiv.innerHTML='<div class="alert-body"> يجب أن يكون التوقيت أحدث من الآن ب 15 دقيقة </div>';
                x = false;
            }
            console.log(users.length);
            if(!x){
                $('html, body').animate({
                    scrollTop: $("#content").offset().top
                }, 1000);
            }

            return x;
        }
    </script>
@endpush
