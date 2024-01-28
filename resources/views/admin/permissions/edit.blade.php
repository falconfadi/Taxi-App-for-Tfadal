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
            <form action="{{url('admin/permissions/update')}}" method="post" >
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

                                <div class="form-group">
                                    <label class="form-label" for="name_ar">{{__('page.name_arabic')}}</label>
                                    <input type="text" id="name_ar" class="form-control dt-uname" placeholder="" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="name_ar" value="{{$perm_->name_ar}}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="name">{{__('page.name_english')}}</label>
                                    <input type="text" id="name" class="form-control dt-uname" placeholder="" aria-label="jdoe1" value="{{$perm_->name}}"   name="name" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="english_question">Main Group ?</label>
                                    <select  id="main_group" name="main_group" class="form-control">
                                        <option value="1">{{__('page.Yes')}} </option>
                                        <option value="0">{{__('page.No')}} </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="english_question"> Group </label>
                                    <select  id="group_id" name="group_id" class="form-control">
                                        <option value="0">--- </option>
                                        @foreach($mainPerms as $mainItem)
                                            @if($mainItem->id==$perm_->group_id)
                                            <option value="{{$mainItem->id}}" selected>{{$mainItem->name}} </option>
                                            @else
                                            <option value="{{$mainItem->id}}">{{$mainItem->name}} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="english_question"> Type </label>
                                    <select  id="type" name="type" class="form-control">
                                        <option value="0">--- </option>
                                        @foreach($types as $key=>$type)
                                            @if($key==$perm_->type)
                                            <option value="{{$key}}" selected>{{$type}} </option>
                                            @else
                                            <option value="{{$key}}" >{{$type}} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="id" value="{{$perm_->id}}" >
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
