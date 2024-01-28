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
                    <h2 class="content-header-title float-left mb-0">{{$title}} </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/drivers/change_password')}}" method="post" >
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{''}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">


                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="arabic_question">{{__('page.Name')}}</label>
                                        <input type="text" id="arabic_question" class="form-control dt-uname"   aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" value="{{$driver->driver_as_user->name}}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="arabic_answer">{{__('page.Phone')}}</label>
                                        <input type="text" id="arabic_answer" class="form-control dt-uname" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" value="{{$driver->driver_as_user->phone}}" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="english_question">{{__('page.Password')}}</label>
                                        <input type="password" id="password" class="form-control dt-uname" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="password" required>
                                    </div>
                                </div>
{{--                                <div class="col-xl-6 col-md-6  col-12 mb-1">--}}

{{--                                    <div class="form-group">--}}
{{--                                        <label class="form-label" for="english_answer">{{__('page.password_confirmation')}}</label>--}}
{{--                                        <input type="password" id="password_confirmation" class="form-control dt-uname" placeholder=""  aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="password_confirmation">--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <input type="hidden" name="id" value="{{$driver->id}}" >
                                <div class="col-sm-9 ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 mt-1">{{__('page.Submit')}}</button>

                                    <a type="button" class="btn btn-info mr-1 mb-1 mt-1" href="{{url()->previous() }}">{{__('menus.back')}} </a>
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
