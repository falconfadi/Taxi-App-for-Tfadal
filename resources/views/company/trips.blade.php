@extends('layouts.admin')
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
                <a class="btn btn-primary waves-effect waves-float waves-light" href="{{url('company/add_trip')}}">{{__('label.add_trip')}}</a>
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

                        <tr>
                            <td><a href="{{url('admin/trips/view/'.$trip->id)}}">{{$trip->serial_num}}</a></td>
                            <td>{{''}}</td>
                            <td>{{($trip->driver_id !=0 )?$trip->driver->name:''}}</td>
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
                                    </div>
                                </div>
                            </td>
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
        // window.setTimeout( function() {
        //     window.location.reload();
        // }, 45000);
    </script>
@endpush

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
