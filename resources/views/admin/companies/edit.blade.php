@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush
@section('content')
    @php  App::setLocale('ar');
    session()->put('locale', 'ar'); @endphp

    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{$title}}</h2>

                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/drivers/update')}}" method="post" enctype="multipart/form-data" onsubmit="return checkValidations()">
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
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li style="padding: 10px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.driver_details')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="name">{{__('page.Name')}}</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{$driver->driver_as_user->name}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="father_name">{{__('page.father_name')}}</label>
                                        <input type="text" class="form-control" name="father_name" id="father_name" value="{{$driver->father_name}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="last_name">{{__('page.last_name')}}</label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" value="{{$driver->last_name}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="phone">{{__('page.Phone')}}</label>
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{$driver->driver_as_user->phone}}" required>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="birthdate">{{__('page.Birthdate')}}</label>
                                        <input type="text" class="form-control" name="birthdate" id="birthdate" value="{{$driver->birthdate}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="helperText">{{__('page.Marital_Status')}}</label>
                                        <select class="custom-select form-control-border" name="marital_status" id="marital_status" required>
                                                @if($driver->marital_status==1)
                                                    <option value="0" selected>{{__('page.Single')}} </option>
                                                    <option value="1" >{{__('page.Married')}} </option>
                                                @else
                                                    <option value="0" >{{__('page.Single')}} </option>
                                                    <option value="1" selected>{{__('page.Married')}}  </option>
                                                @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="helperText">{{__('page.gender')}}</label>
                                        <select class="custom-select form-control-border" name="gender" id="gender" required>
                                            @if($driver->gender==1)
                                                <option value="1" selected>{{__('page.male')}} </option>
                                                <option value="2" >{{__('page.female')}} </option>
                                            @elseif($driver->gender==2)
                                                <option value="1" >{{__('page.male')}} </option>
                                                <option value="2" selected>{{__('page.female')}}  </option>
                                            @else
                                                <option value="1" >{{__('page.male')}} </option>
                                                <option value="2" >{{__('page.female')}}  </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="address">{{__('label.address')}}</label>
                                        <input type="text" class="form-control" name="address" id="address" value="{{($driver->driver_as_user->address)?($driver->driver_as_user->address):''}}" >
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="image">{{__('page.Image')}}</label>
                                        <input type="file" class="form-control" name="image" id="image" >
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="personal_id_image">{{__('page.personal_id_image')}}</label>
                                        <input type="file" class="form-control" name="personal_id_image" id="personal_id_image" >
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="back_personal_id_image">{{__('page.back_personal_id_image')}}</label>
                                        <input type="file" class="form-control" name="back_personal_id_image" id="back_personal_id_image" >
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="driver_id" value="{{$driver->id}}">
                            <input type="hidden" name="back_link" value="{{url()->previous()}}">

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.Related_Car')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="car_type">{{__('page.Car_Type')}}</label>
                                        <select name="car_type" id="car_type" class="form-control">
                                        @foreach($car_types as $car_type)
                                            @if($car_type->id==$car->car_type)
                                            <option value="{{$car_type->id}}" selected>{{$car_type->name}}</option>
                                                @else
                                            <option value="{{$car_type->id}}">{{$car_type->name}}</option>
                                                @endif
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="price_min">{{__('page.Brand')}}</label>
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            @foreach($brands as $brand)
                                                @if($brand->id==$car->mark)
                                                <option value="{{$brand->id}}" selected>{{$brand->brand}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="car_model">{{__('page.Car_Model')}}</label>
                                        <select name="car_model" id="car_model" class="form-control">
                                            @if($car_model)
                                            <option value="{{$car_model->id}}">{{$car_model->model}}</option>
                                            @else
                                            <option value=""></option>
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="plate">{{__('page.Plate')}}</label>
                                        <input type="text" class="form-control" name="plate" id="plate" value="{{$car->plate}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="year">{{__('page.Manifactured_Year')}}</label>
                                        <input type="number" class="form-control" name="year" id="year" value="{{$car->year}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="car_model">{{__('label.color')}}</label>
                                        <select name="color_id" id="color_id" class="form-control">
                                            <option value="0">{{__('label.choose')}}</option>
                                            @foreach($colors as $color)
                                                @if($color->id==$car->color_id)
                                                    <option value="{{$color->id}}" selected>{{$color->color_ar}}</option>
                                                @else
                                                    <option value="{{$color->id}}" >{{$color->color_ar}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="car_image">{{__('page.car_image')}}</label>
                                        <input type="file" class="form-control" name="car_image" id="car_image" >
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-9 ">
                            <button type="submit" class="btn btn-primary mr-1 mb-1 mt-1">{{__('page.Edit')}}</button>
                            <a type="button" class="btn btn-info mr-1 mb-1 mt-1" href="{{url()->previous() }}">{{__('menus.back')}} </a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </section>
        <!-- Basic Inputs end -->



    </div>
@endsection

@push('ajax')
<script>

    $('#brand_id').on('change', function() {
        $("#car_model option").remove();
       var brand_id = document.getElementById('brand_id').value;
        //alert(brand_id);
        var url="{{ route('model.getByBrandId',':brand_id') }}";
        url = url.replace(':brand_id', brand_id);
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',

            success: function( res )
            {
                $.each( res, function(key, value) {
                    //$('#city').append($('<option>', {value:k, text:v}));
                    $('#car_model').append('<option value="'+value.id+'">'+value.model+'</option>');
                });

            },
            error: function()
            {
                //handle errors
                alert('error...');

            }
        });
    });
</script>
@endpush

