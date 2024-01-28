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
                                src="{{($driver->driver_as_user->image)?url('storage/'.$driver->driver_as_user->image):url('storage/users/avatar.png')}}"
                                height="104"
                                width="104"
                                alt="User avatar"
                              />
                              <div class="d-flex flex-column ml-1">
                                <div class="user-info mb-1">
                                  <h4 class="mb-0">{{$driver->driver_as_user->name}}</h4>
                                  <span class="card-text">{{$driver->driver_as_user->email}}</span>
                                </div>
                                <div class="d-flex flex-wrap">
                                  <a href="{{url('admin/drivers/edit/'.$driver->id)}}" class="btn btn-primary">{{__('page.Edit')}}</a>
                                  <button class="btn btn-outline-danger ml-1">{{__('page.Delete')}}</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="d-flex align-items-center user-total-numbers">
                            <div class="d-flex align-items-center mr-2">
                              <div class="color-box bg-light-primary">
                                <i data-feather="dollar-sign" class="text-primary"></i>
                              </div>
                              <div class="ml-1">
                                <h5 class="mb-0">{{$sum_amount}}</h5>
                                <small>{{'المبلغ المحصّل'}}</small>
                              </div>
                            </div>

                          </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                          <div class="user-info-wrapper">
                            <div class="d-flex flex-wrap">
                              <div class="user-info-title">
                                <i data-feather="user" class="mr-1"></i>
                                <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.Marital_Status')}}</span>
                              </div>
                              <p class="card-text mb-0">{{($driver->marital_status==1)?__('page.Married'):__('page.Single')}}</p>
                            </div>
                              <div class="d-flex flex-wrap my-50">
                                  <div class="user-info-title">
                                      <i data-feather="calendar" class="mr-1"></i>
                                      <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.Birthdate')}}</span>
                                  </div>
                                  <p class="card-text mb-0">{{$driver->birthdate}}</p>
                              </div>
                            <div class="d-flex flex-wrap my-50">
                              <div class="user-info-title">
                                <i data-feather="check" class="mr-1"></i>
                                <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.Status')}}</span>
                              </div>
                              <p class="card-text mb-0">{{($driver->verified==1)? __('page.Verified_'):__('page.Verified_')}}</p>
                            </div>
                            <div class="d-flex flex-wrap my-50">
                              <div class="user-info-title">
                                <i data-feather="flag" class="mr-1"></i>
                                <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.Freeze')}}?</span>
                              </div>
                              <p class="card-text mb-0">{{($driver->verified==1)?__('page.Yes'):__('page.No')}}</p>
                            </div>

                            <div class="d-flex flex-wrap">
                              <div class="user-info-title">
                                <i data-feather="phone" class="mr-1"></i>
                                <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.Phone')}}</span>
                              </div>
                              <p class="card-text mb-0">{{$driver->driver_as_user->phone}}</p>
                            </div>
                              <div class="d-flex flex-wrap">
                                  <div class="user-info-title">
                                      <i data-feather='users' class="mr-1"></i>
                                      <span class="card-text user-info-title font-weight-bold mb-0">{{__('page.gender')}}</span>
                                  </div>
                                  <p class="card-text mb-0">{{($driver->driver_as_user->gender!=0)?($driver->driver_as_user->gender==1)?__('page.male'):__('page.female'):''}}</p>
                              </div>
                              <div class="d-flex flex-wrap">
                                  <div class="user-info-title">
                                      <i data-feather='map-pin' class="mr-1"></i>
                                      <span class="card-text user-info-title font-weight-bold mb-0">{{__('label.address')}}</span>
                                  </div>
                                  <p class="card-text mb-0">{{($driver->driver_as_user->address)?($driver->driver_as_user->address):''}}</p>
                              </div>
                              <div class="d-flex flex-wrap">
                                  <div class="user-info-title">
                                      <i data-feather='map-pin' class="mr-1"></i>
                                      <span class="card-text user-info-title font-weight-bold mb-0">{{__('label.last_trip')}}</span>
                                  </div>
                                  <p class="card-text mb-0">{{ (($lastTrips))?$lastTrips[0]:''}}</p>
                              </div>
                          </div>
                        </div>
                      </div>
                        <div class="divider divider-left mt-2">
                            <div class="divider-text"><b>--</b></div>
                        </div>


                        <div class="row mt-2">
                            <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                                <h4 class="mb-1">{{__('page.personal_id_image')}}</h4>
                                <img
                                    class="img-fluid rounded"
                                    src="{{($driver->personal_id_image)?url('storage/'.$driver->personal_id_image):url('storage/users/avatar.png')}}"
                                    height="250"
                                    width="100%"
                                    alt="{{__('page.personal_id_image')}}"
                                />
                            </div>
                            <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                                <h4 class="mb-1">{{__('page.back_personal_id_image')}}</h4>
                                <img
                                    class="img-fluid rounded"
                                    src="{{($driver->back_personal_id_image)?url('storage/'.$driver->back_personal_id_image):url('storage/users/avatar.png')}}"
                                    height="250"
                                    width="100%"
                                    alt="{{__('page.personal_id_image')}}"
                                />
                            </div>

                        </div>

                    </div>
                  </div>
                </div>
                <!-- /User Card Ends-->

              </div>



              <!-- User Invoice Starts-->

              <div class="row invoice-list-wrapper">
                <div class="col-12">
                  <div class="card">
                      <div class="card-header">
                          <h5 class="card-title">{{__('page.Car')}}</h5>

                      </div>
                    <div class="card-datatable table-responsive">
                        @if($car)
                      <table class="invoice-list-table table">
                        <thead>
                          <tr>
                            <th>{{__('page.Brand')}}</th>
                            <th>{{__('page.Car_Model')}}</th>
                            <th>{{__('page.Plate')}} </th>
                            <th>{{__('page.Manifactured_Year')}}</th>

                          </tr>
                        </thead>
                          <tbody>
                          <td>{{$car->brand->brand}}</td>
                          <td>{{$car->carType->name}}</td>
                          <td>{{$car->plate}}</td>
                          <td>{{$car->year}}</td>

                          </tbody>
                      </table>
                        @else
                            <div class="alert alert-primary" role="alert">
                                <div class="alert-body"><strong>No Car!</strong> </div>
                            </div>
                        @endif
                    </div>
                      <div class="card-body">
                          <div class="row mt-2">
                              <div class="col-xl-12 col-lg-12 mt-2 mt-xl-0">
                                  <h4 class="mb-1">{{__('page.car_image')}}</h4>
                                  <img
                                      class="img-fluid rounded"
                                      src="{{($car->image)?url('storage/'.$car->image):url('storage/cars/image-car.jpg')}}"
                                      height="250"
                                      width="100%"
                                      alt="{{__('page.personal_id_image')}}"
                                  />
                              </div>
                          </div>
                      </div>
                  </div>

                </div>
              </div>
              <!-- /User Invoice Ends-->
            </section>

        </div>


@endsection
@push('view-page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/pages/app-user.min.css')}}">
@endpush
