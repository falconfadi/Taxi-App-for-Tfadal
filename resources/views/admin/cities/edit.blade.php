@extends('layouts/admin')

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
            <form action="{{url('admin/cities/update')}}" method="post" >
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
                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="name_ar">{{__('page.name_arabic')}}</label>
                                        <input type="text" id="name_ar" class="form-control dt-uname"  value="{{$city->name_ar}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="name_ar" required>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="name">{{__('page.name_english')}}</label>
                                        <input type="text" id="name" class="form-control dt-uname" value="{{$city->name}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="name" required>
                                    </div>
                                </div>


                                <input type="hidden" name="id" value="{{$city->id}}" >
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
