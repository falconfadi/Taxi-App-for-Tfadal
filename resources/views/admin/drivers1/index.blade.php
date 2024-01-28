@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
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
            <div class="card-header border-bottom ">
                <h5 class="card-title">{{__('menus.Drivers')}}</h5>
                <a type="button" class="btn btn-primary  waves-effect waves-float waves-light" href="{{url('admin/drivers_have_trips')}}">
                    {{__('setting.Drivers_have_trip')}}
                </a>
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table table-bordered" style="width:90%">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.Full_Name')}}</th>
                        <th>{{__('page.Phone')}}</th>
                        <th>{{__('page.gender')}}</th>
                        <th>{{__('page.Verified')}}</th>
                        <th>{{__('setting.connected')}}</th>
                        <th>{{__('menus.created_at')}}</th>
                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($drivers as $driver)
                        @if($driver->drivers_details)
                        <tr>
                            <td><a href="{{url('admin/drivers/view/'.$driver->drivers_details->id)}}" target="_blank">{{$driver->name}}</a></td>
                            <td>{{$driver->phone}}</td>
                            <td>{{($driver->gender!=0)?($driver->gender==1)?__('page.male'):__('page.female'):''}}</td>
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
                            <td>{{$driver->created_at}}</td>
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
                                                <i data-feather="check" class="mr-50"></i>
                                                <span>{{__('page.Verify')}}</span>
                                            </a>
                                        @endif
                                        @if($driver->drivers_details->freeze == 0)
                                            <a class="dropdown-item freeze-button"  data-toggle="modal" data-target="#new-folder-modal"  data-value="{{$driver->drivers_details->id}}">
                                                <i data-feather="stop-circle" class="mr-50"></i>
                                                <span>{{__('page.Freeze')}}</span>
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{url('admin/drivers/unfreeze/'.$driver->drivers_details->id)}}">
                                                <i data-feather="stop-circle" class="mr-50"></i>
                                                <span>{{__('page.Un_Freeze')}}</span>
                                            </a>
                                        @endif
                                        <a class="dropdown-item " href="{{url('admin/drivers/change_password/'.$driver->drivers_details->id)}}">
                                            <i data-feather="eye-off" class="mr-50"></i>
                                            <span>{{__('menus.change_password')}}</span>
                                        </a>
                                        <a class="dropdown-item confirm-text" href="{{url('admin/drivers/delete/'.$driver->drivers_details->id)}}">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
                                        </a>
                                        <a class="dropdown-item confirm-text" href="{{url('admin/drivers/final_delete/'.$driver->drivers_details->id)}}">
                                            <i data-feather="x-circle" class="mr-50"></i>
                                            <span>حذف نهائي</span>
                                        </a>

                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endif

                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Modal to add new user Ends-->
        </div>
        <!-- list section end -->
    </section>
    <!-- users list ends -->

</div>
    <!-- Create New Folder Modal Starts-->
    <div class="modal fade" id="new-folder-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>تجميد حساب</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/drivers/freeze/')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label>السبب</label>
                        <input type="text" class="form-control" name="reason" placeholder="اذكر سبب التجميد" required />
                        <input type="hidden" name="driver_id" id="driver_id" >
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mr-1" >تجميد</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
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

                ],

            });
        } );
    </script>

    <script>
        //get the driver id to modal
        $( ".freeze-button" ).click(function() {
            $('#driver_id').val(this.getAttribute('data-value'));
        });
    </script>
@endpush
