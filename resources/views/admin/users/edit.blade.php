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
                    <h2 class="content-header-title float-left mb-0">{{$title}}</h2>

                </div>
            </div>
        </div>

    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/users/update')}}" method="post" enctype="multipart/form-data">
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
                            <h4 class="card-title">{{$title}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="name">{{__('page.Name')}}</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="phone">{{__('page.Phone')}}</label>
                                        <input type="text" class="form-control dir_ltr" name="phone" id="phone" value="{{$user->phone}}" required>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="helperText">{{__('page.gender')}}</label>
                                        <select class="custom-select form-control-border" name="gender" id="gender" required>
                                            @if($user->gender==1)
                                                <option value="1" selected>{{__('page.male')}} </option>
                                                <option value="2" >{{__('page.female')}} </option>
                                            @elseif($user->gender==2)
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
                                        <label for="address">{{__('page.Address')}}</label>
                                        <input type="text" class="form-control" name="address" id="address" value="{{$user->address}}" >
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="note">{{__('label.note')}}</label>
                                        <input type="text" class="form-control" name="note" id="note" value="{{($user->note)?$user->note->note:''}}" >
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="image">{{__('page.Image')}}</label>
                                        <input type="file" class="form-control" name="image" id="image" >
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <div class="col-sm-9 ">
                                <button type="submit" class="btn btn-primary mr-1 mb-1 mt-1">{{__('page.Edit')}}</button>
                                <a type="button" class="btn btn-info mr-1 mb-1 mt-1" href="{{url()->previous() }}">{{__('menus.back')}} </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            </form>
        </section>
        <!-- Basic Inputs end -->



    </div>
@endsection
