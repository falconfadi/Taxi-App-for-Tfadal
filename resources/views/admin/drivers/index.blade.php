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
            <div class="card-header border-bottom ">
                <h5 class="card-title">{{__('menus.Drivers')}}</h5>

                @if(in_array('view_driver',$permissionsNames) || $isAdmin)
                <a type="button" class="btn btn-primary  waves-effect waves-float waves-light" href="{{url('admin/drivers_have_trips')}}">
                    {{__('setting.Drivers_have_trip')}}
                </a>
                <a type="button" class="btn btn-primary  waves-effect waves-float waves-light" href="{{url('admin/drivers_map')}}">
                    {{__('label.drivers_map')}}
                </a>
                @if(in_array('delete_token_driver',$permissionsNames) || $isAdmin)
                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">{{__('label.delete_token')}}</button>
                @endif
                @if($isAdmin)
                    <button class="btn btn-info waves-effect waves-float waves-light" id="edit_balance">{{__('label.edit_balance')}}</button>
                    @endif
                @endif
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table table-bordered" style="width:90%">
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
                        <th>{{__('page.Car_Type')}}</th>
                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($drivers as $driver)
                        @if($driver->drivers_details)
                        <tr>
                            <td>
                                <a href="{{url('admin/drivers/view/'.$driver->drivers_details->id)}}" target="_blank">{{$driver->name}} {{$driver->drivers_details->father_name}} {{$driver->drivers_details->last_name}}</a>
                                @if(in_array($driver->id,$notAvailableDriversIds))   <span class="mr-50 bullet bullet-success bullet-sm"></span>  @else  <span class="mr-50 bullet bullet-danger bullet-sm"></span> @endif
                            </td>
                            <td>{{$driver->phone}}</td>
                            <td>{{($driver->gender!=0)?($driver->gender==1)?__('page.male'):__('page.female'):''}}</td>
                            <td>{{($driver->drivers_details->verified==1)?__('page.Yes'):__('page.No')}}</td>
                            <td>
                            @if($driver->drivers_details->is_connected==1)
                                <div class="badge-wrapper mr-1">
                                    <div class="badge badge-pill badge-light-success">{{__('setting.connected_')}}</div>
                                </div>
                            @else
                                <div class="badge-wrapper mr-1">
                                    <div class="badge badge-pill badge-light-danger">{{__('setting.not_connected_')}}</div>
                                </div>
                            @endif
                            </td>
                            <td>{{$driver->created_at}}</td>

                            <td>{{($driver->balance)?$driver->balance->balance:0}}</td>
                            <td>{{$driver->drivers_details->birthdate}}</td>
                            <td>{{($freezeReason->isFreezed($driver->id)==1)?__('page.Yes'):__('page.No')}}</td>
                            <td>{{($driver->car_)?$driver->car_->carType->name_ar:'--'}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if(in_array('edit_driver',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item" href="{{url('admin/drivers/edit/'.$driver->drivers_details->id)}}">
                                            <i data-feather="edit-2" class="mr-50"></i>
                                            <span>{{__('page.Edit')}}</span>
                                        </a>
                                        @endif
                                        @if(in_array('verify_driver',$permissionsNames) || $isAdmin)
                                        @if($driver->drivers_details->verified == 0)
                                            <a class="dropdown-item" href="{{url('admin/drivers/verify/'.$driver->drivers_details->id)}}">
                                                <i data-feather="check" class="mr-50"></i>
                                                <span>{{__('page.Verify')}}</span>
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{url('admin/drivers/unverify/'.$driver->drivers_details->id)}}">
                                                <i data-feather="check" class="mr-50"></i>
                                                <span>{{__('label.Un_Verify')}}</span>
                                            </a>
                                        @endif
                                        @endif

                                        @if(in_array('freeze_driver',$permissionsNames) || $isAdmin)
                                        @if($freezeReason->isFreezed($driver->id)==0)
                                            <a class="dropdown-item freeze-button"  data-toggle="modal" data-target="#new-folder-modal"  data-value="{{$driver->drivers_details->id}}">
                                                <i data-feather="stop-circle" class="mr-50"></i>
                                                <span>{{__('page.Freeze')}}</span>
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{url('admin/drivers/unfreeze/'.$driver->drivers_details->id)}}">
                                                <i data-feather="stop-circle" class="mr-50"></i>
                                                <span>{{__('page.Un_Freeze')}}</span>
                                            </a>
                                        @endif
                                        @endif

                                        @if(in_array('change_password_driver',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item " href="{{url('admin/drivers/change_password/'.$driver->drivers_details->id)}}">
                                            <i data-feather="eye-off" class="mr-50"></i>
                                            <span>{{__('menus.change_password')}}</span>
                                        </a>
                                        @endif
                                        @if(in_array('add_note_driver',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#inlineForm" data-id="{{$driver->id}}">
                                            <i data-feather="clipboard"></i>
                                            <span>{{__('label.add_note')}}</span>
                                        </a>
                                        @endif

                                        <a class="dropdown-item add-career-button"  data-toggle="modal" data-target="#add-career-modal"  data-value="{{$driver->id}}">
                                            <i data-feather="stop-circle" class="mr-50"></i>
                                            <span>إضافة مهنة</span>
                                        </a>
                                        @if(in_array('delete_driver',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item confirm-text" href="{{url('admin/drivers/delete/'.$driver->drivers_details->id)}}">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
                                        </a>
                                        @endif

                                        @if(in_array('ultimate_delete_driver',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item confirm-text" href="{{url('admin/drivers/final_delete/'.$driver->drivers_details->id)}}">
                                            <i data-feather="x-circle" class="mr-50"></i>
                                            <span>{{__('label.final_delete')}}</span>
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
            <!-- Modal to add new user Ends-->
        </div>
        <!-- list section end -->
    </section>
    <!-- users list ends -->

</div>
    <!-- freeze Modal Starts-->
    <div class="modal fade" id="new-folder-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>تجميد حساب</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/drivers/freeze/')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label>السبب</label>
                        <input type="text" class="form-control" name="reason" placeholder="اذكر سبب التجميد" required />
                        <input type="hidden" name="driver_id" id="driver_id" >
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mr-1" >تجميد</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- add career Modal Starts-->
    <div class="modal fade" id="add-career-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b> {{__('label.add_career')}}</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/drivers/add_career/')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label>المهنة</label>
                        <select class="form-control" id="career_id" name="career_id">
                            @foreach($careers as $career)
                                <option value="{{$career->id}}">{{$career->name_ar}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="user_id" id="user_id" >
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mr-1" >{{__('label.add')}}</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
        <div class="modal-dialog">
            <form class="add-new-user modal-content pt-0" name="add_offer" action="{{url('admin/drivers/delete_token')}}" method="post">
                @csrf
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('label.delete_token')}}</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="form-group">
                        <label>{{__('label.choose')}}</label>
                        <select class="select2 form-control"  name="user_id" id="user_id">
                            <optgroup label="{{__('menus.Drivers')}}">
                                @foreach($drivers as $user)
                                        <option value="{{$user->id}}" >{{$user->name}} {{$user->drivers_details->father_name}} {{$user->drivers_details->last_name}}-{{$user->phone}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <input type="hidden" name="back_url" id="back_url"  value="{{url()->current()}}">
                    <button type="submit" class="btn btn-primary mr-1 data-submit">{{__('page.Delete')}}</button>
                    <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
<!-- Edit balance-->
<div class="modal modal-slide-in new-user-modal fade" id="modals-edit-balance">
    <div class="modal-dialog">
        <form class="add-new-user modal-content pt-0" name="edit_balance_first" method="post" action="{{url('admin/edit-balance')}}"  id="edit_balance_first" >
            @csrf
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_balance')}}</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="form-group">
                    <label>{{__('label.choose')}}</label>
                    <select class="select2 form-control"  name="driverId" id="driverId">
                        <optgroup label="{{__('menus.Drivers')}}">
                            @foreach($drivers as $user)
                                <option value="{{$user->id}}" >{{$user->name}} {{$user->drivers_details->father_name}} {{$user->drivers_details->last_name}}-{{$user->phone}}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="new_balance">الرصيد الجديد</label>
                    <input type="number" id="new_balance" class="form-control dt-uname"  aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="new_balance" required />
                </div>
                <input type="hidden" name="back_url" id="back_url"  value="{{url()->current()}}">
                <button type="submit"  class="edit_balance btn btn-primary mr-1 data-submit">{{__('page.Edit')}}</button>
                <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
            </div>
        </form>
    </div>
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
                order: [[5, 'desc']]
            });
        } );
    </script>
    <script>
        $(document).ready(function () {
            $('.add_note').on('click', function(e) {

                // var id = $(this).data("id");
                $('#user_id').val($(this).data("id"));
            });
        });
    </script>
    <script>
        //get the driver id to modal
        $( ".freeze-button" ).click(function() {
            $('#driver_id').val(this.getAttribute('data-value'));
        });
    </script>
    <script>
        //get the user id to modal
        $( ".add-career-button" ).click(function() {
            $('#user_id').val(this.getAttribute('data-value'));
        });
    </script>
    <script>
    $( "#add_car" ).click(function() {
        //alert( "Handler for .click() called." );
        $('#modals-slide-in').modal('toggle');
        $('#modals-slide-in').modal('show');
    });
    </script>
    <script>
        $( "#edit_balance" ).click(function() {
            //alert( "Handler for .click() called." );
            $('#modals-edit-balance').modal('toggle');
            $('#modals-edit-balance').modal('show');
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#edit_balance_first').on('submit', function(e) {

                if (confirm("هل تريد بالتأكيد تعديل الرصيد للكابتن")){
                    //$('form#edit_balance_first').submit();
                    return true
                }
                else
                {
                    return false;
                }
            });
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
<div class="form-modal-ex">

    <!-- Modal add note-->
    <div
        class="modal fade text-left"
        id="inlineForm"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel33"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">{{__('label.add_note')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('admin/users/add_note')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label>{{__('label.note')}}</label>
                        <div class="form-group">
                            <input type="text" placeholder="" class="form-control" name="note" required>
                        </div>
                        <input name="user_id" id="user_id" type="hidden">
                        <input type="hidden" name="back_url" id="back_url"  value="{{url()->current()}}">
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
