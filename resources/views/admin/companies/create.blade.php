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
                    <h2 class="content-header-title float-left mb-0">{{__('menus.companies')}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/companies/store')}}" method="post" >
                @csrf
            <div class="row">
                <div class="col-md-12">
                        @if($errors->all())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
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
                                        <input type="text" class="form-control" name="name" id="name"  >
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="model">{{__('page.Phone')}}</label>
                                        <input type="text" class="form-control" name="phone" id="phone"  >
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="model">{{__('page.Email')}}</label>
                                        <input type="text" class="form-control" name="email" id="email"  >
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="model">{{__('page.Password')}}</label>
                                        <input type="password" class="form-control" name="password" id="password"  />
                                        <small class="form-text text-muted width-95-per"> {{__('message.password_must_contain')}} </small>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="model">{{('تأكيد كلمة السر')}}</label>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"  />
                                    </div>
                                </div>

                                <div class="col-sm-9 ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('page.Submit')}}</button>
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
