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
            <!--onsubmit="return checkValidations()" -->
            <form  method="post"  id="add_trip" onsubmit="return checkValidations()">
                @csrf
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('alert-danger'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                {!! session('alert-danger') !!}
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

                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="car_type_id">{{__('page.Car_Type')}}</label>
                                        <select class="custom-select form-control-border" name="car_type_id" id="car_type_id" required>
                                            @foreach($carTypes as $carType)
                                                <option value="{{$carType->id}}">{{$carType->name_ar}}  </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="user" class="form-label">اختر نوع عميل</label>
                                        <select name="user" class="custom-select form-control-border" id="user">
                                            <option value="0">--اختر--</option>
                                            <option value="1">عميل موجود</option>
                                            <option value="2">خدمة عملاء</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="second_number">{{__('page.Phone')}}</label>
                                        <input type="text"  class="form-control" name="second_number" id="second_number"  />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1" id="users_div" style="display: none">
                                    <label for="user_id">{{__('page.User')}}</label>
                                    <select class="select2 form-control"  name="user_id" id="user_id">
                                        <optgroup label="{{__('menus.Users')}}">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}" selected>{{$user->name}}-{{$user->phone}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="name">{{__('page.Type')}}</label>
                                        <select id="type" name="type"  class="form-control">
                                            <option value="1">{{__('label.normal')}}</option>
{{--                                            <option value="2">{{__('label.multi')}}</option>--}}
                                            <option value="3">{{__('label.scheduled')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12 col-12 mb-1" style="display: none" id="trip_date_div">
                                    <div class="form-group">
                                        <label for="model">{{__('page.Trip_Started')}}</label>
                                        <input type="datetime-local"  class="form-control" name="trip_date" id="trip_date"  />
                                    </div>
                                </div>
                                <p style="width: 100%"></p>
                                <div class="col-xl-6 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="from_location">مكان الانطلاق</label>
                                        <input type="text" id="place-input" name="from_location" class="form-control" placeholder="Enter a location">
                                        <input name="from_place_id" type="hidden" id="from_place_id" >
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="to_location">إلى أين؟</label>
                                        <input type="text" id="place-input-target" name="to_location" class="form-control " placeholder="Enter a location">
                                        <input name="to_place_id" type="hidden" id="to_place_id" >
                                    </div>
                                </div>
{{--                                <label for="" class="col-xl-12 col-md-12 font-16"> الرجاء تحريك الخارطة</label>--}}
{{--                                <div class="col-xl-6 col-md-12 col-12 mb5">--}}
{{--                                    <label for="latitude_from">{{__('label.start_trip')}} </label>--}}
{{--                                    <input type="text" class="form-control " name="latitude_from" id="latitude_from"  readonly>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-6 col-md-12 col-12 mb5">--}}
{{--                                    <label for="longitude_from">{{__('label.start_trip')}}</label>--}}
{{--                                    <input type="text" class="form-control "  name="longitude_from" id="longitude_from"  readonly>--}}
{{--                                </div>--}}



{{--                                <div class="col-xl-12 col-md-12 col-12">--}}
{{--                                    <div id="map_canvas" class="map col-xl-12 col-md-12" style="height: 350px"></div>--}}
{{--                                </div>--}}
{{--                                <label for="" class="col-xl-12 col-md-12 font-16 mt-1"> الرجاء تحريك الخارطة</label>--}}
{{--                                <div class="col-xl-6 col-md-12 col-12 mb5 ">--}}
{{--                                    <label for="latitude_to">{{__('label.target_trip')}}</label>--}}
{{--                                    <input type="text" class="form-control " name="latitude_to" id="latitude_to"  readonly>--}}
{{--                                </div>--}}
{{--                                <div class="col-xl-6 col-md-12 col-12 mb5">--}}
{{--                                    <label for="longitude_to">{{__('label.target_trip')}}</label>--}}
{{--                                    <input type="text" class="form-control "  name="longitude_to" id="longitude_to"  readonly>--}}
{{--                                </div>--}}

{{--                                <div class="col-xl-12 col-md-12 col-12">--}}
{{--                                    <div id="map_canvas_2" class="map col-xl-12 col-md-12" style="height: 350px"></div>--}}
{{--                                </div>--}}

                                <div class="col-sm-9 mt-2">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">إظهار السعر</button>
                                    <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url('admin/trips')}}">{{__('menus.back')}} </a>
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

<!-- show trip price-->
<div class="form-modal-ex">
    <!-- Modal -->
    <div class="modal fade text-left" id="inlineForm"   tabindex="-1"    role="dialog" aria-labelledby="myModalLabel33"  aria-hidden="true"    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">تفاصيل الرحلة</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/trips/store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="col-xl-12 col-md-12 col-12 mb-1">
                            <div class="form-group">
                                <label for="distance">{{__('label.expected_distance')}}</label>
                                <input type="text" placeholder="" class="form-control" id="distance" name="distance" readonly>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 col-12 mb-1">
                            <div class="form-group">
                                <label for="duration">{{__('label.expected_duration')}}</label>
                                <input type="text" class="form-control" id="duration" name="duration" readonly>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12 col-12 mb-1">
                            <div class="form-group">
                                <label for="price">{{__('label.expected_price')}}</label>
                                <input type="text" class="form-control" id="price" name="price" readonly>
                            </div>
                        </div>
                        <input name="user_" id="user_" type="hidden">
                        <input name="user_id_" id="user_id_" type="hidden">
                        <input name="car_type_id_" id="car_type_id_" type="hidden">
                        <input name="second_number_" id="second_number_" type="hidden">

                        <input name="latitude_from" id="latitude_from" type="hidden">
                        <input name="longitude_from" id="longitude_from" type="hidden">
                        <input name="location_from" id="location_from" type="hidden">

                        <input name="latitude_to" id="latitude_to" type="hidden">
                        <input name="longitude_to" id="longitude_to" type="hidden">
                        <input name="location_to" id="location_to" type="hidden">

                        <input name="trip_date_" id="trip_date_" type="hidden">
                        <input name="type_" id="type_" type="hidden">

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('label.confirm')}}</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('datatablefooter')

    <script src="https://maps.googleapis.com/maps/api/js?key={{$key}}&libraries=places&language=ar"></script>
    <script>
        function initialize() {
            var input = document.getElementById('place-input');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        var resultsDiv = document.getElementById('results');
                        resultsDiv.innerHTML = this.responseText;
                    }
                };
                $('#from_place_id').val(place.place_id);
                // xhr.open("GET", "process_autocomplete.php?place_id=" + place.place_id, true);
                // xhr.send();
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);

        function initializeTarget() {
            var input = document.getElementById('place-input-target');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        var resultsDiv = document.getElementById('results');
                        resultsDiv.innerHTML = this.responseText;
                    }
                };
                $('#to_place_id').val(place.place_id);
                // xhr.open("GET", "process_autocomplete.php?place_id=" + place.place_id, true);
                // xhr.send();
            });
        }
        google.maps.event.addDomListener(window, 'load', initializeTarget);
    </script>
    <script>
        $('#user').on('change', function() {
            if($(this).val()==1){
                $("#users_div").show();
            }else if($(this).val()==2){
                $("#users_div").hide();
            }
        });
        $('#type').on('change', function() {
            if($(this).val()==3){
                $("#trip_date_div").show();
            }else {
                $("#trip_date_div").hide();
            }
        });
    </script>
    <script>
        $('#add_trip').on('submit',function(e) {
            e.preventDefault();
            var car_type_id = $('#car_type_id').val();
            var from_place_id = $('#from_place_id').val();
            var to_place_id = $('#to_place_id').val();
            var user_id = $('#user_id').val();
            var second_number = $('#second_number').val();
            var user = $('#user').val();
            var trip_date = $('#trip_date').val();
            var type = $('#type').val();
            $('#user_').val(user);
            $('#user_id_').val(user_id);
            $('#car_type_id_').val(car_type_id);
            $('#second_number_').val(second_number);
            $('#trip_date_').val(trip_date);
            $('#type_').val(type);

            //console.log(car_type_id);
            $.ajax({
                url:"{{ route('show_trip_price') }}",
                type:"GET",
                data:{'car_type_id':car_type_id,'from_place_id':from_place_id,'to_place_id':to_place_id},
                success:function (data) {
                    //$('#tbody').empty();
                    $('#inlineForm').modal('show');
                    console.log(data['car_type_id']);
                    $('#distance').val(data['distance']);
                    $('#price').val(data['price']);
                    $('#duration').val(data['duration']);

                    $('#latitude_from').val(data['latitude_from']);
                    $('#longitude_from').val(data['longitude_from']);
                    $('#location_from').val(data['location_from']);

                    $('#latitude_to').val(data['latitude_to']);
                    $('#longitude_to').val(data['longitude_to']);
                    $('#location_to').val(data['location_to']);
                }
            })
        });
    </script>
    </body>
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
            var x = true;

            user = document.getElementById('user').value;
            trip_date = document.getElementById('trip_date').value;
            errormsgdiv = document.getElementById('errormsgdiv');
            errormsgdiv.innerHTML = "";
            //console.log(user);
            if( user==0) {
                errormsgdiv.style.display = "block";
                errormsgdiv.innerHTML = '<div class="alert-body"> يجب أن تختار نوع العميل </div>';
                x = false;
            }


            return x;
        }
    </script>
@endpush
