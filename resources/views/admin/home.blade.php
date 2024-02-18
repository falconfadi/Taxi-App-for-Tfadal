@extends('layouts.admin')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{$title}}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('admin/home')}}">{{__('menus.Home')}}</a>
                            </li>

                            {{--                    <li class="breadcrumb-item active">Layout Empty--}}
                            {{--                    </li>--}}
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
            <div class="form-group breadcrumb-right">
                <div class="dropdown">
{{--                    <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="grid"></i></button>--}}
{{--                    <div class="dropdown-menu dropdown-menu-right">--}}
{{--                        <a class="dropdown-item" href="#"><i class="mr-1" data-feather="check-square"></i>  <span class="align-middle">Todo</span></a>--}}

{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">


        <div class="row">
            <div class="col-12">
                <div class="alert alert-primary" role="alert">
                    <div class="alert-body text-center">
                        <strong></strong>  {{__('auth.Admin_dashboard_App')}} <b><i>{{$appTitle}}</i></b>

                    </div>
                </div>
            </div>
        </div>
        <section class="app-user-list">
            <!-- User Card starts-->

            <div class="col-xl-12 col-lg-8 col-md-7">
                <div class="card user-card">
                    <div class="card-body">
                        <div class="row" style="height: 400px">
                            <img src="{{url('storage/'.$setting->logo)}}" style="height: 400px;margin: 0px auto;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Card Ends-->
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title">{{__('menus.Drivers')}}</h5>
                </div>

                <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                    <table id="example" class="display user-list-table table">
                        <thead class="thead-light">
                        <tr>
                            <th>{{__('page.Full_Name')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($drivers as $driver)
                            <tr>
                                <td><a href="{{url('admin/drivers/view/'.$driver->driver_details_id)}}" target="_blank">{{$driver->name}} {{$driver->father_name}} {{$driver->last_name}}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>>

    </div>


@endsection
