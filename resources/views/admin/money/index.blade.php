@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
@endpush
@section('content')
@php  App::setLocale('ar');
    session()->put('locale', 'ar');@endphp

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
                    <b >{!! session('alert-success') !!}</b>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('alert-danger'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        <b >{!! session('alert-danger') !!}</b>
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
            <div class="card-header border-bottom">
                <h5 class="card-title">{{__('menus.SumOfMoney')}}</h5>
                <button class="btn btn-primary waves-effect waves-float waves-light" id="money_per_month">{{__('page.money_per_month')}}</button>
                <button class="btn btn-primary waves-effect waves-float waves-light" id="renew_balance">{{__('label.renew_balance')}}</button>
            </div>

            <div class="card-datatable table-responsive pt-0 pr-1 pl-1">
                <table id="example" class="display user-list-table table " style="width:90%">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.Full_Name')}}</th>
                        <th>{{__('page.Date')}}</th>
                        <th>{{__('page.whole_money')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=0;@endphp
                    @foreach($requests as $request)
                        @if($request->driver)
                        <tr>
                            <td>{{$request->driver->name." ".$request->driver->drivers_details->father_name." ".$request->driver->drivers_details->last_name}}</td>
                            <td>{{$request->work_day}}</td>
                            <td>{{$request->amount}}</td>

                        </tr>
                        @endif
                        @php $i++;@endphp
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Modal to add new user starts-->
            <div class="modal modal-slide-in new-user-modal fade" id="modals-driver">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0" name="notification" action="{{url('admin/drivers/money_per_month')}}" method="post">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('page.Search')}}</h5>
                        </div>

                        <div class="modal-body flex-grow-1">
                            <div class="modal-body flex-grow-1">

                                <div class="form-group">
                                    <label class="form-label" for="title">{{__('page.Year')}}</label>
                                    <select name="year" id="year" class="form-control">
                                        @for($i=0;$i<10;$i++)
                                        <option value="{{2022+$i}}">{{2022+$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="body">{{__('page.Month')}}</label>
                                    <select name="month" id="month" class="form-control">
                                        <option value="1">{{__('page.Jan')}}</option>
                                        <option value="2">{{__('page.Feb')}}</option>
                                        <option value="3">{{__('page.Mar')}}</option>
                                        <option value="4">{{__('page.Apr')}}</option>
                                        <option value="5">{{__('page.May')}}</option>
                                        <option value="6">{{__('page.Jun')}}</option>
                                        <option value="7">{{__('page.Jul')}}</option>
                                        <option value="8">{{__('page.Aug')}}</option>
                                        <option value="9">{{__('page.Sep')}}</option>
                                        <option value="10">{{__('page.Oct')}}</option>
                                        <option value="11">{{__('page.Nov')}}</option>
                                        <option value="12">{{__('page.Dec')}}</option>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="brand_id">{{__('page.Driver')}}</label>
                                    <select name="driver_id" id="driver_id" class="form-control select2">
                                        <optgroup label="{{__('menus.Drivers')}}">
                                        @forelse($drivers as $driver)
                                            <option value="{{$driver->id}}">{{$driver->name}} {{$driver->drivers_details->father_name}} {{$driver->drivers_details->last_name}}</option>
                                        @empty
                                            <p>{{__('message.No_data')}}</p>
                                        @endforelse
                                        </optgroup>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mr-1 data-submit">{{__('page.Submit')}}</button>
                                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal to add new user Ends-->
            <!-- renew balance -->
            <div class="modal modal-slide-in new-user-modal fade" id="modals_renew_balance">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0" name="notification" action="{{url('admin/drivers/renew_balance')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">{{'تجديد الرصيد'}}</h5>
                        </div>

                        <div class="modal-body flex-grow-1">
                            <div class="modal-body flex-grow-1">
                                <div class="form-group">
                                    <label class="form-label" for="amount">{{__('label.amount')}}</label>
                                    <input type="number" id="amount" class="form-control dt-uname" placeholder="" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="amount" min="0" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="brand_id">{{__('page.Driver')}}</label>
                                    <select name="driver_id" id="driver_id" class="form-control select2">
                                        @forelse($drivers as $driver)
                                            <option value="{{$driver->id}}">{{$driver->name}} {{$driver->drivers_details->father_name}} {{$driver->drivers_details->last_name}}</option>
                                        @empty
                                            <p>{{__('message.No_data')}}</p>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="balance">رقم الإشعار</label>
                                    <input type="text" id="ishaar" class="form-control dt-uname" placeholder="" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="ishaar"  >
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="balance">صورة الإيصال</label>
                                    <input type="file" id="image" class="form-control dt-uname" placeholder="" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="image"  >
                                </div>
                                <button type="submit" class="btn btn-primary mr-1 data-submit">{{'تجديد'}}</button>
                                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                            </div>
                        </div>
                    </form>
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

        $( "#money_per_month" ).click(function() {
            $('#modals-driver').modal('toggle');
            $('#modals-driver').modal('show');
        });
        $( "#renew_balance" ).click(function() {
            $('#modals_renew_balance').modal('toggle');
            $('#modals_renew_balance').modal('show');
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


