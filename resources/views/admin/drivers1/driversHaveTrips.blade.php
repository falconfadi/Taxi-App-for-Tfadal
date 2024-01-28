@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
@php  App::setLocale('ar');
    session()->put('locale', 'ar'); @endphp
</head>
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

            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table " style="width:90%">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.Full_Name')}}</th>
                        <th>{{__('page.Phone')}}</th>
                        <th>{{__('page.Birthdate')}}</th>
                        <th>{{__('page.Marital_Status')}}</th>
                        <th>{{__('page.Verified')}}</th>
                        <th>{{__('setting.connected')}}</th>
                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=0;@endphp
                    @foreach($drivers as $driver)
                        @if($driver->drivers_details)
                        <tr>
                            <td><a href="{{url('admin/drivers/view/'.$driver->drivers_details->id)}}">{{$driver->name}}</a></td>
                            <td>{{$driver->phone}}</td>
{{--                            <td>{{$det[$i]->birthdate}}</td>--}}
                            <td>{{$driver->drivers_details->birthdate}}</td>
                            <td>{{($driver->drivers_details->marital_status==1)?'Single':'Married'}}</td>
                            <td>{{($driver->drivers_details->verified==1)?'Yes':'No'}}</td>
                            <td>
                            @if($driver->drivers_details->is_connected==1)
                                <div class="badge-wrapper mr-1">
                                    <div class="badge badge-pill badge-light-success">{{__('setting.connected_')}}</div>
                                </div>
                            @else
                                <div class="badge-wrapper mr-1">
                                    <div class="badge badge-pill badge-light-danger">{{__('setting.not_connected_')}}</div>
                                </div>
                            @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{url('admin/drivers/edit/'.$driver->drivers_details->id)}}">
                                            <i data-feather="edit-2" class="mr-50"></i>
                                            <span>{{__('page.Edit')}}</span>
                                        </a>
                                        @if($driver->verified != 1)
                                            <a class="dropdown-item" href="{{url('admin/drivers/verify/'.$driver->drivers_details->id)}}">
                                                <i data-feather="edit-2" class="mr-50"></i>
                                                <span>{{__('page.Verify')}}</span>
                                            </a>
                                        @endif
                                        @if($driver->drivers_details->freeze == 0)
                                            <a class="dropdown-item" href="{{url('admin/drivers/freeze/'.$driver->drivers_details->id)}}">
                                                <i data-feather="edit-2" class="mr-50"></i>
                                                <span>{{__('page.Freeze')}}</span>
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{url('admin/drivers/unfreeze/'.$driver->drivers_details->id)}}">
                                                <i data-feather="edit-2" class="mr-50"></i>
                                                <span>{{__('page.Un_Freeze')}}</span>
                                            </a>
                                        @endif
                                        <a class="dropdown-item confirm-text" href="{{url('admin/drivers/delete/'.$driver->drivers_details->id)}}">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
                                        </a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endif
                        @php $i++;@endphp
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Modal to add new user starts-->
            <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" name="user-fullname" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-uname">Username</label>
                                <input type="text" id="basic-icon-default-uname" class="form-control dt-uname" placeholder="Web Developer" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="user-name" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-email">Email</label>
                                <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" aria-describedby="basic-icon-default-email2" name="user-email" />
                                <small class="form-text text-muted"> You can use letters, numbers & periods </small>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="user-role">User Role</label>
                                <select id="user-role" class="form-control">
                                    <option value="subscriber">Subscriber</option>
                                    <option value="editor">Editor</option>
                                    <option value="maintainer">Maintainer</option>
                                    <option value="author">Author</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label" for="user-plan">Select Plan</label>
                                <select id="user-plan" class="form-control">
                                    <option value="basic">Basic</option>
                                    <option value="enterprise">Enterprise</option>
                                    <option value="company">Company</option>
                                    <option value="team">Team</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal to add new user Ends-->
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
    <script>
        $(document).ready(function() {
            url = "{{asset('admin/ar.json')}}";
            $('#example').DataTable({
                language: {
                    url: url,
                },
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                    'print'
                ]
            });

        } );
    </script>
    <script>
        $( "#add_car" ).click(function() {
            //alert( "Handler for .click() called." );
            $('#modals-slide-in').modal('toggle');
            $('#modals-slide-in').modal('show');
        });
    </script>
    <script>
        $( "#add_car" ).click(function() {
            //alert( "Handler for .click() called." );
            $('#modals-slide-in').modal('toggle');
            $('#modals-slide-in').modal('show');
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
                        text: 'Offer has been deleted.',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
            });
        });

    </script>
@endpush

