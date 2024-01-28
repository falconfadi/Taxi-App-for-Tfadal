@extends('layouts/admin')
@section('content')


        <div class="content-body">
            <section class="app-user-view">
              <!-- User Card & Plan Starts -->
              <div class="row">
                <!-- User Card starts-->
                <div class="col-xl-12 col-lg-8 col-md-12">
                  <div class="card user-card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
                          <div class="user-avatar-section">
                            <div class="d-flex justify-content-start">
                              <img
                                class="img-fluid rounded"
                                src="{{url('storage/'.$user->image)}}"
                                height="104"
                                width="104"
                                alt="{{$user->name}}"
                              />
                              <div class="d-flex flex-column ml-1">
                                <div class="user-info mb-1">
                                  <h4 class="mb-0">{{$user->name}}</h4>
                                  <span class="card-text">{{$user->email}}</span>
                                </div>
                                <div class="d-flex flex-wrap">
                                  @can('edit_user')
                                  <a href="{{url('admin/users/edit/'.$user->id)}}" class="btn btn-primary">{{__('page.Edit')}}</a>
                                  @endcan
                                  @can('delete_user')
                                  <a class="btn btn-outline-danger ml-1 confirm-text" href="{{url('admin/users/delete_inside/'.$user->id)}}">{{__('page.Delete')}}</a>
                                  @endcan
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center user-total-numbers">

                          </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                          <div class="user-info-wrapper">

                            <div class="d-flex flex-wrap">
                              <div class="user-info-title">
                                <i data-feather="phone" class="mr-1"></i>
                                <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.Phone')}}</span>
                              </div>
                              <p class="card-text mb-0">{{$user->phone}}</p>
                            </div>
                              <div class="d-flex flex-wrap">
                                  <div class="user-info-title">
                                      <i data-feather='users' class="mr-1"></i>
                                      <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.gender')}}</span>
                                  </div>
                                  <p class="card-text mb-0">{{($user->gender!=0)?($user->gender==1)?__('page.male'):__('page.female'):''}}</p>
                              </div>

                              <div class="d-flex flex-wrap">
                                  <div class="user-info-title">
                                      <i data-feather='map-pin' class="mr-1"></i>
                                      <span class="card-text user-info-title font-weight-bold mb-0">{{__('label.address')}}</span>
                                  </div>
                                  <p class="card-text mb-0">{{($user->address)?($user->address):''}}</p>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /User Card Ends-->
              </div>

            <div class="row invoice-list-wrapper">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{__('menus.Trips')}}</h5>
                        </div>
                        <div class="card-datatable table-responsive">
                                <table class="invoice-list-table table">
                                    <thead>
                                    <tr>
                                        <th>عدد الرحلات</th>
                                        <th><a href="{{url('admin/user_trips/'.$user->id)}}">{{$tripsCount}}</a></th>

                                    </tr>
                                    </thead>
                                </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row invoice-list-wrapper">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{__('label.note')}}</h5>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="invoice-list-table table">
                                <thead>
                                <tr>
                                    <th>{{($user->note)?$user->note->note:'---'}}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row invoice-list-wrapper">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{__('menus.Offers')}}</h5>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="invoice-list-table table">
                                <thead>
                                <th>{{__('page.Details')}}</th>
                                <th>{{'تاريخ النهاية'}}</th>
                                </thead>
                                <tbody>
                                @if($offers)
                                    @forelse($offers as $offer)
                                        <tr>
                                            <th>{{$offer->details}}</th>
                                            <th>{{$offer->end_time}}</th>
                                        </tr>
                                    @empty
                                        <tr> <th colspan="2">{{__('message.No_data')}}</th>             </tr>
                                    @endforelse
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </section>
     </div>


@endsection
@push('view-page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/pages/app-user.min.css')}}">
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
