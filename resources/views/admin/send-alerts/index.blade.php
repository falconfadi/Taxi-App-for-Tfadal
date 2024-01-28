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
        <!-- users filter end -->
        <!-- list section start -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">{{$title}}</h5>

                @if(auth()->user()->can('add_send_alert') || $isAdmin)
                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">{{__('page.Add_New')}}</button>
                @endif
            </div>

            <div class="card-datatable table-responsive pt-0 pr-1 pl-1">
                <table id="example" class="display user-list-table table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.text_arabic')}}</th>
                        <th>{{__('page.text_english')}}</th>
                        <th>{{__('page.Driver')}}</th>
                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($alerts as $alert)
                        @if($alert->driver)
                        <tr>
                            <td>{{$alert->text}}</td>
                            <td>{{$alert->text_en}}</td>
                            <td>{{$alert->driver->name}}</td>
                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{url('admin/faqs/edit/'.$alert->id)}}">
                                            <i data-feather="edit-2" class="mr-50"></i>
                                            <span>{{__('page.Edit')}}</span>
                                        </a>

                                        <a class="dropdown-item confirm-text" href="{{url('admin/faqs/delete/'.$alert->id)}}">
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
            <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0" name="add_offer" action="{{url('admin/send-alerts/store')}}" method="post">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.new_alert')}}</h5>
                        </div>

                        <div class="modal-body flex-grow-1">

                            <div class="form-group">
                                <label class="form-label" for="text">{{__('page.text_arabic')}}</label>
                                <input type="text" id="text" class="form-control dt-uname" placeholder="" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="text" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="text_en">{{__('page.text_english')}}</label>
                                <input type="text" id="text_en" class="form-control dt-uname" placeholder="" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="text_en" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="alert_type">{{__('page.Type')}}</label>
                                <select id="alert_type" name="alert_type" class="form-control">
                                    <option value="1">{{__('page.Alert')}}</option>
                                    <option value="2">{{__('page.contentment')}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="user-role">{{__('page.Driver')}}</label>
                                <select id="driver_id" name="driver_id" class="form-control">
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->name}}</option>
                                @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mr-1 data-submit">{{__('page.Submit')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
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
    <script>
        $(document).ready(function() {
            url = "{{asset('admin/ar.json')}}";
            $('#example').DataTable({
                language: {
                    url: url,
                },
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
