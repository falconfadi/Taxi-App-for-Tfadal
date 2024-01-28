@extends('layouts/admin')

@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')

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
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="name">الاسم </label>
                                        <input type="name" id="name" class="form-control dt-uname"   aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="name" >
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="driver_id">{{__('page.gender')}}</label>
                                        <select class=" form-control " name="gender"  id="gender">
                                            <option value="0">الكل</option>
                                            <option value="1">{{__('page.male')}}</option>
                                            <option value="2">{{__('page.female')}}</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="driver_id">{{__('page.Verified')}}</label>
                                        <select class=" form-control " name="verified"  id="verified">
                                            <option value="2">{{__('label.all')}}</option>:
                                            <option value="1">{{__('page.Yes')}}</option>
                                            <option value="0">{{__('page.No')}}</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="has_trip">{{__('label.has_trip')}}</label>
                                        <select class=" form-control " name="has_trip"  id="has_trip">
                                            <option value="2">{{__('label.all')}}</option>:
                                            <option value="1">{{__('page.Yes')}}</option>
                                            <option value="0">{{__('page.No')}}</option>

                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="id"  >
                                <div class="col-sm-9 ">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('menus.Search')}}</button>
                                    <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url('admin/home') }}">{{__('menus.back')}} </a>
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
                                    <th>{{__('page.Full_Name')}}</th>
                                    <th>{{__('page.Phone')}}</th>
                                    <th>{{__('page.gender')}}</th>
                                    <th>{{__('page.Verified')}}</th>
                                    <th>{{__('setting.connected')}}</th>
                                    <th>{{__('menus.created_at')}}</th>
                                    <th>{{__('label.balance')}}</th>
                                    <th>{{__('page.Birthdate')}}</th>
                                    <th>{{__('label.is_freezed')}}</th>

                                    @if($isAdmin)<th>{{__('page.Actions')}}</th>@endif
                                </tr>
                                </thead>
                                <tbody id="tbody">

                                </tbody>
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
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5',
                        'print'
                    ]
                });

        }
        function  destroytable(){
            $('#example').DataTable().destroy();
        }

            $('#search-form').on('submit',function(e) {
                e.preventDefault();
                destroytable();
                var name = $('#name').val();
                var gender = $('#gender').val();
                 var verified = $('#verified').val();
                 var has_trip = $('#has_trip').val();
                $.ajax({
                    url:"{{ route('driver.search') }}",
                    type:"GET",
                    data:{'name':name,'gender':gender,'verified':verified,'has_trip':has_trip},
                    success:function (data) {

                        $('#tbody').html(data);
                        datatable();

                    }
                })
                // .always(function() {
                //     table.ajax.reload();
                // });
            });




        // $("#search-form").submit(function(e) {
        //     table.ajax.reload();
        // });

</script>
@endpush
