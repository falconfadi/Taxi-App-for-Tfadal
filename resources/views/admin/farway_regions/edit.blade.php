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
            <form action="{{url('admin/farway_regions/update')}}" method="post" >
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

                                        <label class="form-label" for="from">{{__('label.date_from')}}</label>
                                        <input type="text" id="from" class="form-control dt-uname"  value="{{$fa->from}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="from" required>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="to">{{__('label.date_to')}}</label>
                                        <input type="text" id="to" class="form-control dt-uname" value="{{$fa->to}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="to" required>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="price">{{__('label.price_')}}</label>
                                        <input type="text" id="price" class="form-control dt-uname" value="{{$fa->price}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="price" required>
                                    </div>
                                </div>


                                <input type="hidden" name="id" value="{{$fa->id}}" >
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
