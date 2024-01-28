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
                                        <div class="table-responsive border rounded mt-1">
                                            <h6 class="py-1 mx-1 mb-0 font-medium-2">
                                                <i data-feather="lock" class="font-medium-3 mr-25"></i>
                                                <span class="align-middle">{{__('label.permissions')}}</span>
                                            </h6>
                                            <table class="table table-striped table-borderless">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>القائمة</th>
                                                    @for($j=1;$j<=count($types);$j++)
                                                        <th>{{$types[$j]}}</th>
                                                    @endfor
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($groups  as $group)
                                                <tr>
                                                    <td>{{$group->name_ar}}</td>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"  name="permissions[]" value="{{$group->name}}" id="{{$group->id}}" {{ (in_array($group->id,$rolePermissionsIds)) ? "checked" : "" }} />
                                                            <label class="custom-control-label" for="{{$group->id}}"></label>
                                                        </div>
                                                    </td>
                                                    @for($i=1;$i<=count($types);$i++)
                                                    <td>
                                                        @if(isset($permissionsTable[$group->id][$i]) )
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"  name="permissions[]" value="{{$permissionsTable[$group->id][$i]->name}}" id="{{$permissionsTable[$group->id][$i]->id}}" {{ (in_array($permissionsTable[$group->id][$i]->id,$rolePermissionsIds)) ? "checked" : "" }} />
                                                            <label class="custom-control-label" for="{{$permissionsTable[$group->id][$i]->id}}"></label>
                                                        </div>
                                                        @endif
                                                    </td>
                                                    @endfor
                                                </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" value="{{$role->id}}">
                                    <div class="col-sm-9 mt-2">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('page.Edit')}}</button>
                                        <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url('admin/roles')}}">{{__('menus.back')}} </a>
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
