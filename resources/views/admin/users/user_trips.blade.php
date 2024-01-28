@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
    <style>
        .badge{
            padding: 11px 16px 14px 16px ;
        }
    </style>
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
            <!-- users filter end -->
            <!-- list section start -->
            <div class="card">

                <div class="card-header border-bottom">
                    <h5 class="card-title">{{$title}}</h5>
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
                            {{--                            <th>{{__('page.price')}}</th>--}}
                            <th>{{__('page.Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trips as $trip)
                                <tr>
                                    <td><a href="{{url('admin/trips/view/'.$trip->id)}}">{{$trip->serial_num}}</a></td>
                                    @php
                                        if($trip->driver){
                                            $driverName = $trip->driver->name." ".$trip->driver->drivers_details->father_name." ".$trip->driver->drivers_details->last_name;
                                            $url = url('admin/drivers/view/'.$trip->driver->id);
                                        }else{
                                            $driverName = "---";
                                            $url = '#';
                                        }
                                    @endphp
                                    <td><a href="{{url('admin/users/view/'.$trip->user->id)}}">{{$trip->user->name }}</a></td>
                                    <td><a href="{{$url}}">{{$driverName}}</a></td>
                                    <td>{{$trip->start_date}}</td>
                                    <td>{{$status[$trip->status]}}</td>

                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Modal to add new user starts-->

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
                ,  order: [[3, 'desc']]
            });
        } );
    </script>


    <script>
        // window.setTimeout( function() {
        //     window.location.reload();
        // }, 45000);
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




