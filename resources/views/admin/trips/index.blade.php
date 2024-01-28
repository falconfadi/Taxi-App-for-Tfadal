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

                <a type="button" class="btn btn-primary  waves-effect waves-float waves-light" href="{{url('admin/active_trips')}}">
                    {{__('setting.active_trips')}}
                </a>
                <a type="button" class="btn btn-primary  waves-effect waves-float waves-light" href="{{url('admin/scheduled_trips')}}">
                    {{__('menus.Scheduled_Trips')}} {{($scheduledTrips)?count($scheduledTrips):0}}
                </a>
                <a type="button" class="btn btn-primary  waves-effect waves-float waves-light" href="{{url('admin/pending_trips')}}">
                    {{__('setting.pending_trips')}} {{$numOfPendingTrips}}
                </a>

            </div>
        </div>
        <!-- users filter end -->
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
                <h5 class="card-title">{{__('menus.Trips')}}</h5>
                @if(in_array('add_trip',$permissionsNames) || $isAdmin)
                <a href="{{url('admin/trips/create')}}" class="btn btn-primary waves-effect waves-float waves-light" id="add_trip">{{__('menus.Add_trip')}}</a>
                @endif
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
                            <th>{{__('label.note')}}</th>
                            <th>{{__('page.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($trips as $trip)
                        @if($trip->user )
                        <tr>
                            <td><a href="{{url('admin/trips/view/'.$trip->id)}}">{{$trip->serial_num}}</a></td>
                            <td><a href="{{url('admin/users/view/'.$trip->user->id)}}">{{$trip->user->name }}</a></td>
                            @if($trip->driver && $trip->driver->drivers_details)
                                <td><a href="{{url('admin/drivers/view/'.$trip->driver->drivers_details->id)}}">{{$trip->driver->name." ".$trip->driver->drivers_details->father_name." ".$trip->driver->drivers_details->last_name}}</a></td>
                            @else
                                <td>{{"---"}}</td>
                            @endif
                            <td>{{$trip->start_date}}</td>
                            <td>{{$status[$trip->status]}}</td>
                            <td>
                                @php $cleanedNote='';
                                if($trip->noteTrip){
                                     $cleanedNote = trim($trip->noteTrip->note, "\x00..\x1F");
                                    echo substr($cleanedNote, 0, 15)."...";
                                }

                                @endphp

                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
{{--                                        <a class="dropdown-item" href="javascript:void(0);">--}}
{{--                                            <i data-feather="edit-2" class="mr-50"></i>--}}
{{--                                            <span>{{__('page.Edit')}}</span>--}}
{{--                                        </a>--}}
                                        <a class="dropdown-item" href="{{url('admin/trips/view/'.$trip->id)}}">
                                            <i data-feather="eye" class="mr-50"></i>
                                            <span>{{__('page.View')}}</span>
                                        </a>

                                        @if(!in_array($trip->status,[4,5]))
                                        <a class="dropdown-item cancel-trip" data-toggle="modal" data-target="#cancel-trip-with-reason"  data-value="{{$trip->id}}"  >
                                            <i data-feather='stop-circle' class="mr-50"></i>
                                            <span>{{__('page.Cancel')}}</span>
                                        </a>
                                        @endif

                                        @if(!in_array($trip->status,[4,5]) && !is_null($trip->start_date))
                                        <a class="dropdown-item end_trip" href="#" data-toggle="modal" data-target="#end_trip" data-id="{{$trip->id}}">
                                            <i data-feather='clipboard'></i>
                                            <span>{{__('label.end_trip')}}</span>
                                        </a>
                                        @endif
                                        @if(!in_array($trip->status,[4,5]) )
{{--                                            <a class="dropdown-item transform_captain" href="#" data-toggle="modal" data-target="#transform_captain" data-id="{{$trip->id}}">--}}
{{--                                                <i data-feather='clipboard'></i>--}}
{{--                                                <span>{{__('label.transform_captain')}}</span>--}}
{{--                                            </a>--}}
                                        @endif
                                        @if(!$trip->noteTrip)
                                        <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#inlineForm" data-id="{{$trip->id}}">
                                            <i data-feather='clipboard'></i>
                                            <span>{{__('label.add_note')}}</span>
                                        </a>
                                        @endif
                                        @if(in_array('delete_trip',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item confirm-text" href="{{url('admin/trip/delete/'.$trip->id)}}">
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
        //get the trip id to modal
        $( ".cancel-trip" ).click(function() {
            $('#trip_id').val(this.getAttribute('data-value'));
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.add_note').on('click', function(e) {
                // var id = $(this).data("id");
                $('#trip_id_').val($(this).data("id"));

            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.end_trip').on('click', function(e) {
                // var id = $(this).data("id");
                $('#tripId').val($(this).data("id"));
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.transform_captain').on('click', function(e) {
                // var id = $(this).data("id");
                $('#trip_Id').val($(this).data("id"));
            });
        });
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
<!-- add cancel reason -->
<div class="modal fade" id="cancel-trip-with-reason">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>{{__('label.cancel_trip')}}</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('admin/trips/cancel')}}" method="post">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label class="form-label" for="city_id">{{__('page.reason')}}</label>
                        <select  id="reason_id" name="reason_id" class="form-control ">
                            @forelse($cancelReasons as $reason)
                                <option value="{{$reason->id}}">{{$reason->arabic_title}} </option>
                            @empty
                                <p>No Data</p>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{__('label.note')}}</label>
                        <input type="text" class="form-control" name="reason_text" placeholder="أدخل نصاً"  />
                    </div>

                    <input type="hidden" name="trip_id" id="trip_id" >
                    <input type="hidden" name="back_url" id="back_url"  value="{{url()->current()}}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-1" >{{__('label.save')}}</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- add note-->
<div class="form-modal-ex">
    <!-- Modal -->
    <div class="modal fade text-left" id="inlineForm"   tabindex="-1"    role="dialog" aria-labelledby="myModalLabel33"  aria-hidden="true"    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">{{__('label.add_note')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/trips/add_note')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label>{{__('label.note')}}</label>
                        <div class="form-group">
                            <input type="text" placeholder="" class="form-control" name="note" required>
                        </div>
                        <input name="trip_id_" id="trip_id_" type="hidden">
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

<!-- end trip-->
<div class="form-modal-ex">
    <!-- Modal -->
    <div class="modal fade text-left" id="end_trip"   tabindex="-1"  role="dialog" aria-labelledby="myModalLabel33"  aria-hidden="true"    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">{{__('label.end_trip')}}</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="myModalLabel33">{{__('message.end_trip_sure')}}</h5>
                </div>

                <form action="{{url('admin/trips/end_trip')}}" method="post">

                    @csrf
                    <input name="tripId" id="tripId" type="hidden">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('page.Yes')}}</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- transform captain-->
<div class="form-modal-ex">
    <!-- Modal -->
    <div class="modal fade text-left" id="transform_captain" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel33"  aria-hidden="true"    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">{{__('label.transform_captain')}}</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/trips/transform_captain')}}" method="post">
                    @csrf

                    <div class="modal-body">
                        <label>{{__('label.driver_name')}}</label>
                        <div class="form-group">
                            <select  id="driver_id" name="driver_id" class="form-control select2">
                                @forelse($availableDrivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->name}} {{$driver->drivers_details->last_name}}</option>
                                @empty
                                    <p>No Data</p>
                                @endforelse
                            </select>
                        </div>
                        <input name="trip_Id" id="trip_Id" type="hidden">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('label.Change')}}</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

