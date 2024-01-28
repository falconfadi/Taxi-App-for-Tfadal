@extends('layouts/admin')
@section('content')
    <link rel="stylesheet" href="{{asset('admin/richtexteditor/rte_theme_default.css')}}" />

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
            <form  method="post" id="privacy-form">

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
                                    <label for="arabic_privacy">{{__('page.privacy_policy_ar')}}</label>
                                    <div id="div_editor1">
                                        {{$policy->arabic_privacy}}
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-12 mb-1">
                                    <label for="arabic_privacy">{{__('page.privacy_policy_en')}}</label>
                                    <div id="div_editor2">
                                        {{$policy->english_privacy}}
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

                                    <button type="button" class="btn-submit btn btn-primary mr-1 mb-1">{{__('page.Edit')}}</button>

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

@push('richtext')
    <script type="text/javascript" src="{{asset('admin/richtexteditor/rte.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/richtexteditor/plugins/all_plugins.js')}}"></script>
    <script>
        var editor1 = new RichTextEditor("#div_editor1");
        var editor2 = new RichTextEditor("#div_editor2");
        //editor1.setHTMLCode("Use inline HTML or setHTMLCode to init the default content.");
    </script>
@endpush

@push('')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".btn-submit").click(function(e){

            e.preventDefault();

            var arabic_privacy = $("#div_editor1").text();
            console.log(arabic_privacy);
            // var password = $("input[name=password]").val();
            // var email = $("input[name=email]").val();

            $.ajax({
                type:'POST',
                url:"{{ route('privacy.edit') }}",
                data:{arabic_privacy:arabic_privacy},
                success:function(data){
                   // alert(data.success);
                    alert(data.success);
                }
            });

        });
    </script>
@endpush
