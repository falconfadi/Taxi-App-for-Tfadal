@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
    <style>
        .font17{
            font-size: 17px;
        }
    </style>
<div class="content-header row">
</div>
<div class="content-body">
    <!-- users list start -->
    <section class="app-user-list">
        <!-- users filter start -->
        <div class="card">

            <div class="card-header">

                @if(in_array('send notification',$permissionsNames) || $isAdmin)
                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">{{__('page.Send_Notifications_to_Users')}}</button>
                @endif
{{--                <button class="btn btn-dark waves-effect waves-float waves-light" id="send_notifications_drivers">{{__('page.Send_Notifications_to_Drivers')}} </button>--}}
            </div>
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

            <div class="card-header">
                <h5 class="card-title">{{$title}}</h5>

            </div>

            <div class="card-datatable table-responsive pt-0 pr-1 pl-1">
                <table id="example" class="display user-list-table table" style="width:90%">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.Title')}}</th>
                        <th>{{__('page.Body')}}</th>
                        <th>{{__('page.Type')}}</th>
                        <th>{{__('page.User')}}</th>
                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($notifications as $notification)
                       @foreach($notification->users as $user)
                        <tr>
                            <td>{{$notification->title}}</td>
                            <td>{{$notification->body}}</td>
                            <td>{{($notification->notification_type== 1)?__('advertisement'):__('trip')}}</td>
                            <td>
                                @if($notification->is_all == 1 )
                                    {{__('label.all')}}
                                @else
                                    {{$user->name}}
                                @endif
                            </td>
                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if(in_array('delete_notification',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item confirm-text" href="{{url('admin/notifications/delete/'.$notification->id)}}">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
                                        </a>
                                        @endif
                                    </div>
                                </div>

                            </td>
                        </tr>
                       @endforeach

                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal to add notification user -->
            <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0" name="add_offer" action="{{url('admin/notifications/store')}}" method="post">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('page.New_Notification')}}</h5>
                        </div>

                        <div class="modal-body flex-grow-1">
                            <div class="form-group">
                                <label class="form-label" for="title">{{__('page.Title')}}</label>
                                <input type="text" class="form-control dt-full-name font17" name="title" id="title"  aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" required />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="body">{{__('page.Body')}}</label>
                                <input type="text" class="form-control dt-full-name font17" name="body" id="body"  aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                            </div>

                            <div class="form-group">
                                <label class="switch-label" for="price_open">{{__('label.all')}}</label><br>
                                <div class="custom-control custom-switch custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="all" value="1" />
                                    <label class="custom-control-label" for="customSwitch1"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{__('page.sent_to')}}</label>
                                <select name="users[]" class="select2 form-control" multiple  id="users">
                                    <optgroup label="Users">
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name."-".$user->phone}}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mr-1 data-submit">{{__('label.send')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal to add notification user ends-->
        </div>
        <!-- list section end -->
    </section>
    <!-- users list ends -->

</div>
@endsection
@push('datatablefooter')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script>
        $(document).ready(function() {
            url = "{{asset('admin/ar.json')}}";
            $('#example').DataTable({
                language: {
                    url: url,
                },
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'copyHtml5',
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'csvHtml5',
                    'pdfHtml5',
                    'print',
                    'colvis',
                ]
            });
        } );
    </script>
    <script>
        $( "#add_car" ).click(function() {
            $('#modals-slide-in').modal('toggle');
            $('#modals-slide-in').modal('show');
        });
        $( "#send_notifications_drivers" ).click(function() {
            //alert( "Handler for .click() called." );
            $('#modals-driver').modal('toggle');
            $('#modals-driver').modal('show');
        });
        //disable choose people
        $('#customSwitch1').change(function () {
            W = $(this).val();
            if($(this).is(":checked")){
                //alert("check");
                $('#users').prop('disabled', 'disabled');
            }else{
                //alert("uncheck");
                $('#users').prop('disabled', false);
            }
        });
        $('#customSwitch2').change(function () {
            W = $(this).val();
            if($(this).is(":checked")){
                //alert("check");
                $('#drivers').prop('disabled', 'disabled');
            }else{
                //alert("uncheck");
                $('#drivers').prop('disabled', false);
            }
        });
    </script>
@endpush
@push('sweetalert')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/polyfill.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <script>
        // Confirm Text
        var confirmText = $('#confirm-text');
        $('.confirm-text').on('click', function (event) {
            //confirmText.on('click', function () {
            event.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.value) {
                    window.location.href = url;
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Notification has been deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
            });
        });

    </script>
@endpush

@push('select2')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('admin/app-assets/js/scripts/forms/form-select2.min.js')}}"></script>
    <!-- END: Page JS-->
@endpush
