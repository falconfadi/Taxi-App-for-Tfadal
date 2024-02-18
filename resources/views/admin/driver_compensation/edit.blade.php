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
            <form action="{{url('admin/update_compensation_')}}" method="post" >
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

                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="arabic_answer">Long when approve</label>
                                        <input type="text" id="arabic_answer" class="form-control dt-uname" value="{{$comp->longitude}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="longitude" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="arabic_question">Lat when approve</label>
                                        <input type="text" id="arabic_question" class="form-control dt-uname"  value="{{$comp->latitude}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="latitude" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="english_question">distance</label>
                                        <input type="text" id="english_question" class="form-control dt-uname" value="{{$comp->distance}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="distance" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6  col-12 mb-1">

                                    <div class="form-group">
                                        <label class="form-label" for="english_answer">Compensation</label>
                                        <input type="text" id="english_answer" class="form-control dt-uname" placeholder="" value="{{$comp->amount}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="amount" required>
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="{{$comp->id}}" >
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
