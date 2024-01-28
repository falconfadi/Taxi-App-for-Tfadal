@extends('layouts.admin')
@push('datatableheader')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
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
            <div class="card-header">
                <h5 class="card-title">{{$title}}</h5>
{{--                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">Add New</button>--}}
            </div>
            <div class="card-body">
                <p class="card-text">

                </p>
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <a type="button" class="btn btn-outline-primary btn-block mb-1" href="{{url('admin/trips_form/num_of_trips')}}"><b class="font-16">   الطلبات بين تاريخين بحسب السائقين</b></a>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <a type="button" class="btn btn-outline-primary btn-block mb-1" href="{{url('admin/trips_form/num_of_trips_cancelled')}}"><b class="font-16">   الطلبات الملغية بين تاريخين بحسب السائقين</b></a>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <a type="button" class="btn btn-outline-primary btn-block mb-1" href="{{url('admin/trips_form/trips_prob')}}"><b class="font-16">  إحصائيات الرحلات</b></a>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <a type="button" class="btn btn-outline-primary btn-block mb-1" href="{{url('admin/trips_form/trips_prob_cancelled')}}"><b class="font-16">   إحصائيات الرحلات الملغية</b></a>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <a type="button" class="btn btn-outline-primary btn-block mb-1" href="{{url('admin/trips_form/trips_money')}}"><b class="font-16">دخل الشركة من الرحلات</b></a>
                    </div>
                </div>
            </div>


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


