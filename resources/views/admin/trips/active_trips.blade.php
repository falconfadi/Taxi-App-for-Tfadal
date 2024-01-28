@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
<div class="content-header row">
</div>
<div class="content-body">
    <!-- users list start -->
    <section class="app-user-list">
        <!-- users filter start -->
        <div class="card">
            <div class="card-header border-bottom">

            </div>
        </div>
        <!-- list section start -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title">{{__('setting.active_trips')}}</h5>
{{--                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">Add New</button>--}}
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{__('page.User')}}</th>
                            <th>{{__('page.Driver')}}</th>
                            <th>{{__('page.Start_Date')}}</th>
                            <th>{{__('page.Status')}}</th>
                            <th>{{__('page.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($trips as $trip)
                        @if($trip->user && $trip->driver)
                        <tr>
                            <td><a href="{{url('admin/trips/view/'.$trip->id)}}">{{$trip->serial_num}}</a></td>
                            <td><a href="{{url('admin/users/view/'.$trip->user->id)}}">{{$trip->user->name}}</a></td>
                            @if($trip->driver && $trip->driver->drivers_details)
                            <td><a href="{{url('admin/drivers/view/'.$trip->driver->drivers_details->id)}}">{{$trip->driver->name." ".$trip->driver->drivers_details->father_name." ".$trip->driver->drivers_details->last_name}}</td>
                            @else
                                <td>{{"---"}}</td>
                            @endif
                            <td>{{$trip->start_date}}</td>
                            <td>{{$status[$trip->status]}}</td>
                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">

                                        <a class="dropdown-item" href="{{url('admin/trips/view/'.$trip->id)}}">
                                            <i data-feather="eye" class="mr-50"></i>
                                            <span>{{__('page.View')}}</span>
                                        </a>

                                        @if(in_array('delete_trip',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item" href="javascript:void(0);">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
                                        </a>
                                        @endif
                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- list section end -->
        <a type="button" class="btn btn-info" href="{{url('admin/trips')}}">عودة</a>
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
@endpush

