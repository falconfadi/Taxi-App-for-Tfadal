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

            <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
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
                            <th>{{__('label.text')}}</th>
                            <th>{{__('page.Reason')}}</th>
                            <th>{{__('menus.created_at')}}</th>

                        </tr>
                    </thead>
                    <tbody>
                    @foreach($trips as $trip)
                        <tr>
                            <td><a href="{{url('admin/trips/view/'.$trip->id)}}">{{$trip->serial_num}}</a></td>
                            <td>
                            @if(!is_null($trip->user) && !is_null($trip->user->id))
                            <a href="{{url('admin/users/view/'.$trip->user->id)}}">{{($trip->user)?$trip->user->name:''}}</a>
                            @else
                                <a href="#">---</a>
                            @endif
                            </td>
                            <td>
                            @if(!is_null($trip->driver) && !is_null($trip->driver->id))
                                <a href="{{url('admin/drivers/view/'.$trip->driver->id)}}">{{($trip->driver)?$trip->driver->name:''}}</a>
                            @else
                                <a href="#">---</a>
                            @endif
                            </td>
                            <td>{{($trip->reasonText)?$trip->reasonText->reason_text:''}}</td>
                            <td>{{($trip->reasonText)?(($trip->reasonText->cancelReason)?$trip->reasonText->cancelReason->arabic_title:''):''}}</td>
                            <td>{{$trip->trip_date}}</td>
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
                ,  order: [[5, 'desc']]
            });
        } );
    </script>
@endpush

