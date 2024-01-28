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
            <div class="card-header border-bottom">
                <h5 class="card-title">{{$title}}</h5>
                <button class="btn btn-primary waves-effect waves-float waves-light" id="add_car">{{__('page.Add_New')}}</button>
            </div>

            <div class="card-datatable table-responsive pt-1 pr-1 pl-1">
                <table id="example" class="display user-list-table table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{__('page.name_english')}} </th>
                        <th>{{__('page.name_arabic')}}</th>
                        <th>{{__('page.Price_per_Kilometer')}}</th>
                        <th>{{__('page.Image')}}</th>
                        <th>{{__('page.Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($car_types as $car_type)
                        <tr>
                            <td>{{$car_type->name}}</td>
                            <td>{{$car_type->name_ar}}</td>
                            <td>{{$car_type->price}}</td>
                            <td><img src="{{url('storage/'.$car_type->image)}}" style="width: 300px"> </td>
                            <td>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if(in_array('edit_car_type',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item" href="{{url('admin/car-types/edit/'.$car_type->id)}}">
                                            <i data-feather="edit-2" class="mr-50"></i>
                                            <span>{{__('page.Edit')}}</span>
                                        </a>
                                        @endif
                                        @if(in_array('delete_car_type',$permissionsNames) || $isAdmin)
                                        <a class="dropdown-item check_drivers"  data-id="{{$car_type->id}}" >
                                            <i data-feather="trash" class="mr-50"></i>
                                            <span>{{__('page.Delete')}}</span>
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
            <!-- Modal to add new user starts-->
            <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                <div class="modal-dialog">
                    <form class="add-new-user modal-content pt-0" name="add_offer" action="{{url('admin/car-types/store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('page.New_Car_Type')}}</h5>
                        </div>
                        <div class="modal-body flex-grow-1">
                            <div class="form-group">
                                <label class="form-label" for="name">{{__('page.name_english')}}</label>
                                <input type="text" class="form-control dt-full-name" name="name" id="name"   aria-label="" aria-describedby="basic-icon-default-fullname2" />
                                <span id="name-error" class="error" style="display:none">مطلوب.</span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="start_time">{{__('page.name_arabic')}}</label>
                                <input type="text" id="name_ar" class="form-control dt-uname" placeholder="" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="name_ar" />
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="price">{{__('page.Price_per_Kilometer')}}</label>
                                <input type="number" id="price" name="price" class="form-control " step="0.1"  aria-describedby="basic-icon-default-email2"  />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="other_type">{{__('page.other_type_link')}}</label>

                                <select name="other_type" id="other_type" class="form-control">
                                    <option value="0" selected> {{__('page.not_found')}} </option>
                                    @forelse($car_types as $type)
                                        <option value="{{$type->id}}">{{$type->name_ar}} </option>
                                    @empty
                                        <p>No Data</p>
                                    @endforelse
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="minute_price">{{__('label.minute_price')}}</label>
                                <input type="number" id="minute_price" name="minute_price" class="form-control " step="0.1"  aria-describedby="basic-icon-default-email2"  />
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="price">{{__('page.Image')}}</label>
                                <input type="file" id="image" name="image" class="form-control "   />
                            </div>

                            <button type="submit" class="btn btn-primary mr-1 data-submit">{{__('page.Submit')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{__('page.Cancel')}}</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal to add new user Ends-->

            <div
                class="modal fade modal-danger text-left"
                id="danger"
                tabindex="-1"
                role="dialog"
                aria-labelledby="myModalLabel120"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel120">تحذير</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           لايمكنك حذف هذه الفئة لأنها مرتبطة بـ كباتن موجودين
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">موافق</button>
                        </div>
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
        $( "#add_car" ).click(function() {
            //alert( "Handler for .click() called." );
            $('#modals-slide-in').modal('toggle');
            $('#modals-slide-in').modal('show');
        });

    </script>
    <script>
        // Wait for the DOM to be ready
        $(function() {
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $("form[name='add_offer']").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    details: "required",
                    start_date: "required",
                    code: {
                        required: true,
                        minlength: 5
                    }
                },
                // Specify validation error messages
                messages: {
                    details: "Please enter details",
                    start_date: "Please enter your start date",
                    code: {
                        required: "Please provide a password",
                        minlength: "The code must be at least 5 characters long"
                    },
                    email: "Please enter a valid email address"
                },
                // Make sure the form is submitted to the destination defined
                // in the "action" attribute of the form when valid
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>

    <script>
        $('.check_drivers').on('click',function(e) {
            e.preventDefault();
           // $('#add-emplyee').hide();
            var id = $(this).data("id") ;
            console.log(id);
            $.ajax({
                url:"{{ route('drivers.check') }}",
                type:"GET",
                data:{'id':id},
                success:function (data) {
                    if(data){
                        $('#danger').modal('toggle');
                        $('#danger').modal('show');
                    }else{

                        window.location.href = '{{url('admin/car-types')}}';
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف!',
                            text: 'تم حذف عنصر من القائمة',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    }


                }
            })
        });
    </script>
@endpush
@push('sweetalert')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/polyfill.min.js')}}"></script>
    <!-- END: Page Vendor JS-->
    <script>


    </script>
@endpush

