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
                    <h2 class="content-header-title float-left mb-0">{{__('menus.Car_Models')}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/roles/update')}}" method="post" >
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
                                            <label for="model">{{__('page.Name')}}</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{$role->name}}" />
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-12">


                                        <div class="form-group">
                                            <label for="brand_id">{{'الصلاحيات'}}</label>

                                            <div class="table-responsive">
                                                <table class="table table-striped table-borderless">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('page.Name')}}</th>
                                                        <th>إضافة \ إلغاء</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($perms as $permission)
                                                        <tr>
                                                            <td>{{$permission->name_ar}}</td>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->name}}" id="{{$permission->id}}" {{ (in_array($permission->id,$rolePermissionsIds)) ? "checked" : "" }}  />
                                                                    <label class="custom-control-label" for="{{$permission->id}}"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$role->id}}">
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
