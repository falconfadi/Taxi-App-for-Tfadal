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
                @if(auth()->user()->can('add_role') || $isAdmin)
                <a class="btn btn-primary waves-effect waves-float waves-light" id="" href="{{url('admin/roles/create')}}">{{__('page.Add_New')}}</a>
                @endcan
            </div>

            <div class="card-datatable table-responsive pt-0 pr-1 pl-1">
                <table id="example" class="display user-list-table table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.Name')}}</th>

                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        @if($role->name!='Super-Admin')
                        <tr>
                            <td>{{$role->name}}</td>

                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{url('admin/roles/edit/'.$role->id)}}">
                                            <i data-feather="edit-2" class="mr-50"></i>
                                            <span>{{__('page.Edit')}}</span>
                                        </a>
                                        <a class="dropdown-item confirm-text" href="{{url('admin/roles/delete/'.$role->id)}}">
                                            <i data-feather="trash" class="mr-50 " ></i>
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
                    <form class="add-new-user modal-content pt-0" name="add_offer" action="{{url('admin/car-models/store')}}" method="post">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">موديل جديد</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
                            <div class="form-group">
                                <label class="form-label" for="model">{{__('page.Car_Model')}}</label>
                                <input type="text" class="form-control dt-full-name" name="model" id="model"   aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="brand_id">{{__('page.Brand')}}</label>
                                <select name="brand_id" id="brand_id" class="form-control">

                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mr-1 data-submit">{{__('page.Submit')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">إلغاء</button>
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

