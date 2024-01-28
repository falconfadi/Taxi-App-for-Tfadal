@extends('layouts/admin')
@push('datatableheader')
    {{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">--}}
    {{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">--}}
    {{--    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush
@section('content')

{{--<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">--}}


<section class="app-user-list">
    <!-- users filter start -->
    <div class="card">

        <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
            <div class="col-md-4 user_role"></div>
            <div class="col-md-4 user_plan"></div>
            <div class="col-md-4 user_status"></div>
        </div>
    </div>
    <!-- users filter end -->
    <!-- list section start -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><b>{{$title}}</b></h5>
            {{--                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">Add New</button>--}}
        </div>
        <div class="card-body">
            <p class="card-text">

            </p>
            <div class="row">

                <div class="col-lg-12 col-md-12">

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>  عدد الرحلات من {{$from}} إلى  {{$to}}</span>
                            <span class="badge badge-primary badge-pill">{{$sumTrips}}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>


        <div class="container px-4 mx-auto">

            <div class=" bg-white rounded shadow">
                {!! $chart->container() !!}
            </div>

        </div>
        <div class="col-sm-6 ">
            <a type="button" class="btn btn-info mr-1 mb-1 mt-1" href="{{url('admin/kpi_trips')}}">عودة </a>
        </div>

    </div>
    <!-- list section end -->


</section>

<script src="{{ $chart->cdn() }}"></script>

{{ $chart->script() }}
@endsection
