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
            <div class="card-header border-bottom">
                <h5 class="card-title">{{$title}}</h5>
                @if(in_array('delete_token_user',$permissionsNames) || $isAdmin)
                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">{{__('label.delete_token')}}</button>
                @endif
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.Name')}}</th>
                        <th>{{__('page.Phone')}}</th>
                        <th>{{__('page.gender')}}</th>
                        <th>{{__('page.Address')}}</th>
                        <th>{{__('menus.created_at')}}</th>
                        <th>{{__('label.sum_trip_acheived')}}</th>
                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <a target="_blank" href="{{url('admin/users/view/'.$user->id)}}">{{$user->name}}-{{$user->phone}}</a>
                                @if(in_array($user->id,$notAvailableDriversIds))   <span class="mr-50 bullet bullet-success bullet-sm"></span>  @else  <span class="mr-50 bullet bullet-danger bullet-sm"></span> @endif
                            </td>
                            <td>{{$user->phone}}</td>
                            <td>{{($user->gender!=0)?($user->gender==1)?__('page.male'):__('page.female'):''}}</td>
                            <td>{{$user->address}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>{{$sumOftrips[$user->id]}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if(in_array('edit_user',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item" href="{{url('admin/users/edit/'.$user->id)}}">
                                            <i data-feather="edit-2" class="mr-50"></i>
                                            <span>{{__('page.Edit')}}</span>
                                        </a>
                                        @endif
                                        @if(in_array('change_password_user',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item " href="{{url('admin/users/change_password/'.$user->id)}}">
                                            <i data-feather="eye-off" class="mr-50"></i>
                                            <span>{{__('menus.change_password')}}</span>
                                        </a>
                                        @endif

                                        @if(in_array('freeze_user',$permissionsNames) || $isAdmin)
                                            @if($freezeReason->isFreezed($user->id)==0)
                                            <a class="dropdown-item freeze-button"  data-toggle="modal" data-target="#new-folder-modal"  data-value="{{$user->id}}">
                                                <i data-feather="stop-circle" class="mr-50"></i>
                                                <span>{{__('page.Freeze')}}</span>
                                            </a>
                                            @else
                                            <a class="dropdown-item" href="{{url('admin/users/unfreeze/'.$user->id)}}">
                                                <i data-feather="stop-circle" class="mr-50"></i>
                                                <span>{{__('page.Un_Freeze')}}</span>
                                            </a>
                                            @endif
                                        @endif
                                        @if(in_array('add_note_user',$permissionsNames) || $isAdmin)
                                        @if(!$user->note)
                                        <a class="dropdown-item add_note" href="#" data-toggle="modal" data-target="#inlineForm" data-id="{{$user->id}}">
                                            <i data-feather='clipboard'></i>
                                            <span>{{__('label.add_note')}}</span>
                                        </a>
                                        @endif
                                        @endif
                                        <a class="dropdown-item add-career-button"  data-toggle="modal" data-target="#add-career-modal"  data-value="{{$user->id}}">
                                            <i data-feather="stop-circle" class="mr-50"></i>
                                            <span>إضافة مهنة</span>
                                        </a>

                                        @if(in_array('delete_user',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item confirm-text" href="{{url('admin/users/delete/'.$user->id)}}">
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
                                        </a>
                                        @endif
                                        @if( $isAdmin)
                                        <a class="dropdown-item confirm-text" href="{{url('admin/users/final_delete/'.$user->id)}}">
                                            <i data-feather="x-circle" class="mr-50"></i>
                                            <span>حذف نهائي</span>
                                        </a>
                                        @endif

                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>


        </div>



        <!-- list section end -->
    </section>
    <!-- users list ends -->

</div>

<!-- freeze modal-->
<div class="modal fade" id="new-folder-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>تجميد حساب</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('admin/users/freeze/')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label>السبب</label>
                    <input type="text" class="form-control" name="reason" placeholder="اذكر سبب التجميد" required />
                    <input type="hidden" name="user_id_" id="user_id_" >
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-1" >تجميد</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- delete token-->
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
                            @foreach($users as $user)
                                <option value="{{$user->id}}" selected>{{$user->name}}-{{$user->phone}}</option>
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

<!-- add career Modal Starts-->
<div class="modal fade" id="add-career-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b> إضافة مهنة</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('admin/users/add_career/')}}" method="post">
                @csrf
                <div class="modal-body">
                    <label>المهنة</label>
                    <select class="form-control" id="career_id" name="career_id">
                        @foreach($careers as $career)
                            <option value="{{$career->id}}">{{$career->name_ar}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="userId" id="userId" >
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-1" >{{__('label.add')}}</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                </div>
            </form>
        </div>
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

                ]
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
            $('#user_id_').val(this.getAttribute('data-value'));
        });
    </script>
    <script>
        //get the user id to modal
        $( ".add-career-button" ).click(function() {
            $('#userId').val(this.getAttribute('data-value'));
        });
    </script>
    <script>
    $( "#add_car" ).click(function() {
    //alert( "Handler for .click() called." );
    $('#modals-slide-in').modal('toggle');
    $('#modals-slide-in').modal('show');
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




