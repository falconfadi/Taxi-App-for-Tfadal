@extends('layouts/admin')
@section('content')
    <script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
<style>
    .switch-label{
        font-size: 15px;
    }
</style>
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
            <form action="{{url('admin/privacy_policy/update')}}" method="post" >
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
                            <h4 class="card-title">{{__('page.policy_and_who_we_ar')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="arabic_privacy">{{__('page.privacy_policy_ar')}}</label>
                                        <textarea name="arabic_privacy" style="width: 100%;">       {{$policy->arabic_privacy}}
                                        </textarea><br>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="english_privacy">{{__('page.privacy_policy_en')}}</label>
                                        <textarea name="english_privacy" style="width: 100%;">       {{$policy->english_privacy}}
                                        </textarea><br>
                                    </div>
                                </div>

                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="arabic_terms">{{__('page.terms_arabic')}}</label>
                                        <textarea name="arabic_terms" style="width: 100%;">       {{$term->arabic_terms}}
                                        </textarea><br>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="english_terms">{{__('page.terms_english')}}</label>
                                        <textarea name="english_terms" style="width: 100%;">       {{$term->english_terms}}
                                        </textarea><br>
                                    </div>
                                </div>
                                <div class="col-sm-9 ">
                                    @can('edit_who_we_are')
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('page.Edit')}}</button>
                                    @endcan
                                    <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url()->previous() }}">{{__('menus.back')}} </a>
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
