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
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errormsgdiv" style="display: none">
        <div class="alert-body">
            {!! session('alert-danger') !!}
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
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
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/change_password/store')}}" method="post" class="add-new-user modal-content pt-0"  onsubmit="return checkValidations()">
                @csrf
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$title}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="english_question">{{__('page.Password')}}</label>
                                        <input type="password" id="password" class="form-control dt-uname" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="password" >
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="english_question">{{__('label.password_confirmation')}}</label>
                                        <input type="password" id="password_confirmation" class="form-control dt-uname" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="password_confirmation" >
                                    </div>
                                </div>

                                <div class="col-sm-9 ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 mt-1">{{__('page.Submit')}}</button>
                                    <a type="button" class="btn btn-info mr-1 mb-1 mt-1" href="{{url()->previous() }}">{{__('menus.back')}} </a>
                                </div>
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

@push('form_validation')
    <script type="text/javascript">
        function checkValidations()
        {
            //alert(document.getElementById('starttime').value);
            var x = true;
            password = document.getElementById('password').value;
            password_confirmation = document.getElementById('password_confirmation').value;

            errormsgdiv = document.getElementById('errormsgdiv');
            errormsgdiv.innerHTML = "";

            if( password== '' || password_confirmation == '')
            {
                errormsgdiv.style.display = "block";
                errormsgdiv.innerHTML='<div class="alert-body"> جميع الحقول المطلوبة </div>';
                x = false;
            }
            else if(password != password_confirmation){
                errormsgdiv.style.display = "block";
                errormsgdiv.innerHTML='<div class="alert-body"> يجب تطابق كلمة السر مع تأكيد كلمة السر </div>';
                x = false;
            }
            return x;
        }
    </script>
@endpush


