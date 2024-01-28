@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush

@push('datepicker_header')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
@endpush
@push('datepicker_header2')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/core/menu/menu-types/vertical-menu.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/plugins/forms/pickers/form-flat-pickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/plugins/forms/pickers/form-pickadate.min.css')}}">
@endpush

@section('content')

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
            <form action="{{url($fullURL)}}" method="post" >
                @csrf
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('alert-danger'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                            <h4 class="card-title">{{$title}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-12 col-6 mb-1">
                                    <div class="form-group">
                                        <label for="fp-human-friendly">مِن</label>
                                        <input type="text"
                                            id="fp-human-friendly" name="from"
                                            class="form-control flatpickr-human-friendly"
                                            placeholder="October 14, 2020"  />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12 col-6 mb-1">
                                    <div class="form-group">
                                        <label for="fp-human-friendly">إلى</label>
                                        <input type="text"
                                               id="fp-human-friendly1" name="to"
                                               class="form-control flatpickr-human-friendly"
                                               placeholder="October 14, 2020"  />
                                    </div>

                                </div>

                                <div class="col-sm-6 ">
                                    <a type="button" class="btn btn-info mr-1 mb-1" href="{{url('admin/kpi_trips')}}">إلغاء</a>
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
@push('datepicker')
    <script src="{{ asset('admin/app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{ asset('admin/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{ asset('admin/app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{ asset('admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
@endpush

@push('datepicker2')
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('admin/app-assets/js/scripts/forms/pickers/form-pickers.min.js')}}"></script>
    <!-- END: Page JS-->
@endpush
