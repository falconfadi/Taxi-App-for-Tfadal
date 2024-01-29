@extends('layouts/admin')
@push('datatableheader')
    <meta name="csrf-token" content="{{ csrf_token() }}">



@endpush
@section('content')

<div class="content-header row">
</div>
<div class="content-body">
    <!-- users list start -->
    <section class="app-user-list">
        <!-- users filter start -->
        <div class="card">
            <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
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
        </div>
        <!-- users filter end -->
        <!-- list section start -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title">{{$title}}</h5>
                @if(in_array('delete_token_user',$permissionsNames) || $isAdmin)
                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">{{__('label.delete_token')}}</button>
                @endif
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table data-table">
                    <thead>
                    <tr>

                        <th>Name</th>
                        <th>Email</th>
                        <th width="100px">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


        </div>



        <!-- list section end -->
    </section>
    <!-- users list ends -->

</div>

<!-- freeze modal-->
<div class="modal fade" id="new-folder-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>تجميد حساب</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('admin/users/freeze/')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label>السبب</label>
                    <input type="text" class="form-control" name="reason" placeholder="اذكر سبب التجميد" required />
                    <input type="hidden" name="user_id_" id="user_id_" >
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-1" >تجميد</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- delete token-->
<div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
    <div class="modal-dialog">
        <form class="add-new-user modal-content pt-0" name="add_offer" action="{{url('admin/drivers/delete_token')}}" method="post">
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">{{__('label.delete_token')}}</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="form-group">
                    <label>{{__('label.choose')}}</label>
                    <select class="select2 form-control"  name="user_id" id="user_id">
                        <optgroup label="{{__('menus.Drivers')}}">
                            @foreach($users as $user)
                                <option value="{{$user->id}}" selected>{{$user->name}}-{{$user->phone}}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <input type="hidden" name="back_url" id="back_url"  value="{{url()->current()}}">



                <button type="submit" class="btn btn-primary mr-1 data-submit">{{__('page.Delete')}}</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
            </div>
        </form>
    </div>
</div>

<!-- add career Modal Starts-->
<div class="modal fade" id="add-career-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b> إضافة مهنة</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('admin/users/add_career/')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label>المهنة</label>
                    <select class="form-control" id="career_id" name="career_id">
                        @foreach($careers as $career)
                            <option value="{{$career->id}}">{{$career->name_ar}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="userId" id="userId" >
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-1" >{{__('label.add')}}</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('datatablefooter')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
{{--    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
{{--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>--}}
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <style>



        div.dataTables_wrapper {
            direction: rtl;
        }
        th, td{
            white-space: nowrap;
        }
        .dataTables_length {
            float: right;
        }
        .dataTables_filter {
            float: right;
            text-align: right;
        }
        #DataTables_Table_0_last {
            -moz-border-radius-bottomright: 0px;
            -webkit-border-bottom-right-radius: 0px;
            -khtml-border-bottom-right-radius: 0px;
            border-bottom-right-radius: 0px;

            -moz-border-radius-topright: 0px;
            -webkit-border-top-right-radius: 0px;
            -khtml-border-top-right-radius: 0px;
            border-top-right-radius: 0px;

            -moz-border-radius-bottomleft: 6px;
            -webkit-border-bottom-left-radius: 6px;
            -khtml-border-bottom-left-radius: 6px;
            border-bottom-left-radius: 6px;

            -moz-border-radius-topleft: 6px;
            -webkit-border-top-left-radius: 6px;
            -khtml-border-top-left-radius: 6px;
            border-top-left-radius: 6px;
        }
        #DataTables_Table_0_first {
            -moz-border-radius-bottomright: 6px;
            -webkit-border-bottom-right-radius: 6px;
            -khtml-border-bottom-right-radius: 6px;
            border-bottom-right-radius: 6px;

            -moz-border-radius-topright: 6px;
            -webkit-border-top-right-radius: 6px;
            -khtml-border-top-right-radius: 6px;
            border-top-right-radius: 6px;

            -moz-border-radius-bottomleft: 0px;
            -webkit-border-bottom-left-radius: 0px;
            -khtml-border-bottom-left-radius: 0px;
            border-bottom-left-radius: 0px;

            -moz-border-radius-topleft: 0px;
            -webkit-border-top-left-radius: 0px;
            -khtml-border-top-left-radius: 0px;
            border-top-left-radius: 0px;
        }
        .dataTables_info {
            float: right;
        }
        .dataTables_paginate {
            float: left;
            text-align: left;
        }
    </style>
    <script>
        $( "#add_car" ).click(function() {
            //alert( "Handler for .click() called." );
            $('#modals-slide-in').modal('toggle');
            $('#modals-slide-in').modal('show');
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.add_note').on('click', function(e) {

               // var id = $(this).data("id");
                $('#user_id').val($(this).data("id"));

            });
        });
    </script>
    <script>
        //get the driver id to modal
        $( ".freeze-button" ).click(function() {
            $('#user_id_').val(this.getAttribute('data-value'));
        });
    </script>
    <script>
        //get the user id to modal
        $( ".add-career-button" ).click(function() {
            $('#userId').val(this.getAttribute('data-value'));
        });
    </script>
    <script type="text/javascript">
        $(function () {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });
    </script>
@endpush
@push('select2')
    <!-- BEGIN: Page Vendor JS-->
{{--    <script src="{{ asset('admin/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>--}}
{{--    <!-- END: Page Vendor JS-->--}}
{{--    <!-- BEGIN: Page JS-->--}}
{{--    <script src="{{ asset('admin/app-assets/js/scripts/forms/form-select2.min.js')}}"></script>--}}
    <!-- END: Page JS-->
@endpush
<div class="form-modal-ex">

    <!-- Modal add note-->
    <div
        class="modal fade text-left"
        id="inlineForm"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel33"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">{{__('label.add_note')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/users/add_note')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label>{{__('label.note')}}</label>
                        <div class="form-group">
                            <input type="text" placeholder="" class="form-control" name="note" required>
                        </div>
                        <input name="user_id" id="user_id" type="hidden">
                        <input type="hidden" name="back_url" id="back_url"  value="{{url()->current()}}">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('label.save')}}</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


