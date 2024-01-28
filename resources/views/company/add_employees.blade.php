@extends('layouts.admin')
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
            <form action="{{url('company/update')}}" method="post" id="search-form">
                @csrf
            <div class="row">
                <div class="col-md-12">
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
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errors" style="display: none">
                            <div class="alert-body">
                                {{__('message.No_data')}}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('menus.add_employee')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
{{--                                <div class="col-xl-12 col-md-12 col-12 mb-1">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="model">{{__('page.Name')}}</label>--}}
{{--                                        <input type="text" class="form-control" name="name" id="name"  >--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-xl-12 col-md-12 col-12 ">
                                    <div class="form-group">
                                        <label for="model">{{__('label.search_by_phone')}}</label>
                                        <input type="tel" class="form-control" name="phone" id="phone"  >
                                    </div>
                                </div>
                                <div class="col-sm-9 ">
                                    <a type="button" class="btn btn-info mr-1 " href="{{url('company/employees') }}">{{__('menus.back')}} </a>
                                    <button type="submit" class="btn btn-primary mr-1 ">{{__('page.Search')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            </form>
            <form action="{{url('company/add_employee_store')}}" method="post" id="add-emplyee" style="display: none">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-12 ">
                            <div class="form-group">
                                <label for="model">{{__('page.Name')}}</label>
                                <input type="text" class="form-control" name="emp_name" id="emp_name"  readonly>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="phone_" id="phone_"  >
                        <div class="col-sm-9 ">
                            <button type="submit" class="btn btn-primary mr-1 ">{{__('label.add')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <!-- Basic Inputs end -->
    </div>
@endsection
@push('datatablefooter')
    <script>
        $('#search-form').on('submit',function(e) {
            e.preventDefault();
            $('#add-emplyee').hide();
            var phone = $('#phone').val();
            console.log(phone)
            $.ajax({
                url:"{{ route('phone.search') }}",
                type:"GET",
                data:{'phone':phone},
                success:function (data) {

                    if(data){
                        $('#add-emplyee').show();
                        $('#phone_').val(phone);
                        $('#emp_name').val(data.name);
                    }else{
                        $('#errors').show();

                    }
                    //$('#tbody').empty();
                    //table.clear();
                    //table.fnReloadAjax();
                    //table.draw();

                    //$('#tbody').html(data);
                    //table.destroy();
                    //table.reload();
                }
            })
        });
    </script>
@endpush
