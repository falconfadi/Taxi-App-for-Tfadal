@extends('layouts/admin')

@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
    @php

    @endphp
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
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{$title}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Basic Inputs start -->

        <section id="basic-input">
            <form method="get" id="search-form" >
                @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$title}}</h4>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="driverid">{{__('page.Driver')}}</label>
                                        <select class="select2 form-control form-control-lg" name="driverid"  id="driverid">
                                            @foreach($drivers as $driver)
                                                @if($driver->drivers_details)
                                                <option value="{{$driver->id}}">{{$driver->name." ".$driver->drivers_details->father_name." ".$driver->drivers_details->last_name."-".$driver->phone}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="date">{{__('label.date_from')}}</label>
                                        <input type="date" id="date" class="form-control dt-uname"   aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="date" >
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="date_to">{{__('label.date_to')}}</label>
                                        <input type="date" id="date_to" class="form-control dt-uname"   aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="date_to" >
                                    </div>
                                </div>

                                <input type="hidden" name="id"  >
                                <div class="col-sm-9 ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('menus.Search')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                            <table id="example" class="display user-list-table table">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{__('page.Driver')}}</th>
                                    <th>{{__('label.amount')}}</th>
                                    <th>{{__('page.Type')}}</th>
                                    <th>{{__('label.trip')}}</th>
                                    <th>{{__('page.company_percentage')}}</th>
                                    <th>{{__('page.Date')}}</th>
                                </tr>

                                </thead>
                                <tbody id="tbody">
                                    <tr>
                                        <td><b>{{__('label.balance')}}</b></td>
                                        <td id="balance"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>

                                <tfoot id="tfoot"></tfoot>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
            </form>
        </section>
        <!-- Basic Inputs end -->

    </div>




@endsection
@push('select2')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin/app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('admin/app-assets/js/scripts/forms/form-select2.min.js')}}"></script>
    <!-- END: Page JS-->
@endpush

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
        let table;
        function  datatable(table) {

            url = "{{asset('admin/ar.json')}}";
            table = $('#example').DataTable({
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
                        },
                        footer:true
                    },
                    { extend:'csvHtml5', footer: true},
                    { extend:'pdfHtml5',footer: true},
                    { extend:'print',footer: true},
                    { extend:'colvis',footer: true},

                ],
             /*   orderFixed: [ 1, "desc" ],*/
            });

        }
        function  destroytable(){
            $('#example').DataTable().destroy();
        }

        $('#search-form').on('submit',function(e) {
            e.preventDefault();
            destroytable();
            $('#tbody').empty();$('#tfoot').empty();
            var driver_id = $('#driverid').val();
            console.log(driver_id);
            var date = $('#date').val();
            var date_to = $('#date_to').val();

            $.ajax({
                url:"{{ route('balance.search') }}",
                type:"GET",
                data:{'driver_id':driver_id,'date':date,'date_to':date_to},
                success:function (data) {

                    $('#tbody').append(data['tbody']);
                    $('#tfoot').html(data['tfoot']);
                    $('#balance').html(data['balance']);
                    //table.destroy();
                    datatable();
                }
            })
        });
</script>


@endpush
