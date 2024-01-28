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
                    <h2 class="content-header-title float-left mb-0">Edit warning </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/send-alerts/update')}}" method="post" >
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
                                        <label class="form-label" for="arabic_question">Arabic Question</label>
                                        <input type="text" id="arabic_question" class="form-control dt-uname"  value="{{$faq->arabic_question}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="arabic_question" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="arabic_answer">Arabic Answer</label>
                                        <input type="text" id="arabic_answer" class="form-control dt-uname" value="{{$faq->arabic_answer}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="arabic_answer" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="english_question">English Question</label>
                                        <input type="text" id="english_question" class="form-control dt-uname" value="{{$faq->english_question}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="english_question" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6  col-12 mb-1">

                                    <div class="form-group">
                                        <label class="form-label" for="english_answer">English Answer</label>
                                        <input type="text" id="english_answer" class="form-control dt-uname" placeholder="" value="{{$faq->english_answer}}" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="english_answer" required>
                                    </div>
                                </div>

                                <input type="hidden" name="id" value="{{$faq->id}}" >
                                <div class="col-sm-9 ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">Submit</button>
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
