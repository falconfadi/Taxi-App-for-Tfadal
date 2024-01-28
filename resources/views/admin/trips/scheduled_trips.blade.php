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
        </div>



        <!-- list section start -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title">{{__('menus.Scheduled_Trips')}} </h5>
{{--                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">Add New</button>--}}
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{__('page.User')}}</th>
                            <th>{{__('label.time_of_create_trip')}}</th>
                            <th>{{__('page.Status')}}</th>
                            <th>{{__('label.target')}}</th>
                            <th>{{__('page.Trip_Started')}}</th>
                            <th>{{__('page.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($trips as $trip)
                        @if($trip->user )
                        <tr>
                            <td><a href="{{url('admin/trips/view/'.$trip->id)}}">{{$trip->serial_num}}</a></td>
                            <td><a href="{{url('admin/users/view/'.$trip->user->id)}}">{{$trip->user->name}}</a></td>
                            <td>{{$trip->created_at}}</td>
                            <td>{{$status[$trip->status]}}</td>
                            <td>{{$trip->location_to}}</td>
                            <td>{{$trip->trip_date}}</td>
                            <td>
{{--                                <div class="dropdown">--}}
{{--                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">--}}
{{--                                        <i data-feather="more-vertical"></i>--}}
{{--                                    </button>--}}
{{--                                    <div class="dropdown-menu">--}}
{{--                                        <a class="dropdown-item add_driver" data-toggle="modal" data-target="#add_driver"  data-value="{{$trip->id}}">--}}
{{--                                            <i data-feather="plus" class="mr-50"></i>--}}
{{--                                            <span>{{__('label.add_driver')}}</span>--}}
{{--                                        </a>--}}

{{--                                        <a class="dropdown-item cancel-trip" data-toggle="modal" data-target="#cancel-trip-with-reason"  data-value="{{$trip->id}}"  >--}}
{{--                                            <i data-feather='stop-circle' class="mr-50"></i>--}}
{{--                                            <span>{{__('page.Cancel')}}</span>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
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


<!-- add cancel reason -->
{{--<div class="modal fade" id="cancel-trip-with-reason">--}}
{{--    <div class="modal-dialog modal-dialog-centered">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title"><b>{{__('label.cancel_trip')}}</b></h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <form action="{{url('admin/trips/cancel')}}" method="post">--}}
{{--                @csrf--}}
{{--                <div class="modal-body">--}}

{{--                    <div class="form-group">--}}
{{--                        <label class="form-label" for="city_id">{{__('page.reason')}}</label>--}}
{{--                        <select  id="reason_id" name="reason_id" class="form-control">--}}
{{--                            @forelse($cancelReasons as $reason)--}}
{{--                                <option value="{{$reason->id}}">{{$reason->arabic_title}} </option>--}}
{{--                            @empty--}}
{{--                                <p>No Data</p>--}}
{{--                            @endforelse--}}
{{--                        </select>--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label>{{__('label.note')}}</label>--}}
{{--                        <input type="text" class="form-control" name="reason_text" placeholder="أدخل نصاً"  />--}}
{{--                    </div>--}}

{{--                    <input type="hidden" name="trip_id" id="trip_id" >--}}
{{--                    <input type="hidden" name="back_url" id="back_url"  value="{{url()->current()}}">--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="submit" class="btn btn-primary mr-1" >{{__('label.save')}}</button>--}}
{{--                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


{{--<!-- add driver -->--}}
{{--<div class="modal fade" id="add_driver">--}}
{{--    <div class="modal-dialog modal-dialog-centered">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title"><b>{{__('label.add_driver')}}</b></h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <form action="{{url('admin/trips/add_driver')}}" method="post">--}}
{{--                @csrf--}}
{{--                <div class="modal-body">--}}

{{--                    <div class="form-group">--}}
{{--                        <label class="form-label" for="city_id">{{__('menus.Drivers')}}</label>--}}
{{--                        <select class="select2 form-control form-control-lg" name="driver_id" id="driver_id">--}}
{{--                            @forelse($drivers as $driver)--}}
{{--                            <option value="{{$driver->id}}">{{$driver->name}} {{$driver->drivers_details->father_name}} {{$driver->drivers_details->last_name}}{{"-".$driver->phone}}</option>--}}
{{--                            @empty--}}
{{--                            <option value="0">{{__('message.No_data')}}</option>--}}
{{--                            @endforelse--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    <input type="hidden" name="trip_id_" id="trip_id_"  >--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="submit" class="btn btn-primary mr-1" >{{__('label.add')}}</button>--}}
{{--                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
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
        $(document).ready(function () {
            $('.add_driver').on('click', function(e) {
                $('#trip_id_').val($(this).data("value"));
            });
        });
    </script>
    <script>
        //get the trip id to modal
        $( ".cancel-trip" ).click(function() {
            $('#trip_id').val(this.getAttribute('data-value'));
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

