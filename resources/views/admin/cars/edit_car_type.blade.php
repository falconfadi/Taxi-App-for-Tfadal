@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush
@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0"></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/car-types/update')}}" method="post" enctype="multipart/form-data">
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
                                        <label for="name">{{__('page.name_english')}}</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{$carType->name}}" />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="name_ar">{{__('page.name_arabic')}}</label>
                                        <input type="text" class="form-control" name="name_ar" id="name_ar" value="{{$carType->name_ar}}" />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="price">{{__('page.Price_per_Kilometer')}}</label>
                                        <input type="number" class="form-control" name="price" id="price" value="{{$carType->price}}" />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="other_type">{{__('page.other_type_link')}}</label>
                                        <select name="other_type" id="other_type" class="form-control">
                                            @if($carType->other_type==0)
                                                <option value="0" selected> {{__('page.not_found')}} </option>
                                                @forelse($car_types as $type)
                                                    <option value="{{$type->id}}">{{$type->name_ar}} </option>
                                                @empty
                                                    <p>No Data</p>
                                                @endforelse
                                            @else
                                                @forelse($car_types as $type)
                                                    @if($type->other_type == $carType->other_type  )
                                                        <option value="{{$type->id}}" selected> {{$type->name_ar}} </option>
                                                    @else
                                                        <option value="{{$type->id}}">{{$type->name_ar}} </option>
                                                    @endif
                                                @empty
                                                    <p>No Data</p>
                                                @endforelse
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="minute_price">{{__('label.minute_price')}}</label>
                                        <input type="number" id="minute_price" name="minute_price" class="form-control " step="0.1"  value="{{$carType->minute_price}}"  aria-describedby="basic-icon-default-email2"  />
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="price">{{__('page.Image')}}</label>
                                        <input type="file" class="form-control" name="image" id="image" />
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{$carType->id}}" >
                                <div class="col-sm-9 ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('page.Edit')}}</button>
                                    <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url()->previous() }}">{{__('menus.back')}} </a>
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
