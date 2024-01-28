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

        <!-- list section start -->
        <div class="card">
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
            @if(Session::has('alert-danger'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        {!! session('alert-danger') !!}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card-header border-bottom">
                <h5 class="card-title">{{$title}}</h5>
{{--                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">Add New</button>--}}
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{__('page.Driver')}}</th>
                            <th>{{__('label.trip')}}</th>
                            <th>{{__('label.distance')}}</th>
                            <th>{{__('label.value')}}</th>

                            <th>{{__('page.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $i=1; @endphp
                    @foreach($driverCompensation as $item)
                    @if($item->trip)
                        <tr>

                            <th>{{$i++}}</th>
                            <td>{{( $item->trip->driver)?$item->trip->driver->name." ".$item->trip->driver->drivers_details->father_name." ".$item->trip->driver->drivers_details->last_name:''}}</td>
                            <td>{{$item->trip->serial_num}}</td>
                            <td>{{$item->distance}}</td>
                            <td>{{$item->amount}}</td>
                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
{{--                                        <a class="dropdown-item" href="javascript:void(0);">--}}
{{--                                            <i data-feather="edit-2" class="mr-50"></i>--}}
{{--                                            <span>{{__('page.Edit')}}</span>--}}
{{--                                        </a> @if(in_array('delete_user',$permissionsNames) || $isAdmin)--}}


                                        <a class="dropdown-item confirm-text" href="{{url('admin/driver-compensation/delete/'.$item->id)}}">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
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
                ,  order: [[2, 'desc']]
            });
        } );
    </script>
    <script>
        //get the trip id to modal
        $( ".cancel-trip" ).click(function() {
            $('#trip_id').val(this.getAttribute('data-value'));
        });
    </script>
@endpush

