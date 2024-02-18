@extends('layouts/admin')
@push('datatableheader')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush
@section('content')
<style>
    .switch-label{
        font-size: 15px;
    }
</style>
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{$title}}</h2>
                    @if(in_array('edit_setting',$permissionsNames) || $isAdmin)
                    @if($setting->maintenance_status==0)
                        <a type="button" class="btn btn-danger  waves-effect waves-float waves-light confirm_maintenance"  href="{{url('admin/settings/maintenance')}}" style="float:left">
                            {{'تفعيل وضع الصيانة'}}
                        </a>
                    @else
                        <a type="button" class="btn btn-primary  waves-effect waves-float waves-light disactive_confirm_maintenance" href="{{url('admin/settings/disactive_maintenance')}}" style="float:left">
                            {{' إلغاء تفعيل وضع الصيانة'}}
                        </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div class="content-body"><!-- Basic Inputs start -->
        <section id="basic-input">
            <form action="{{url('admin/settings/update')}}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="row">
                <div class="col-md-12">

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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.General_Settings')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="phone">{{__('page.Phone')}}</label>
                                        <input type="text" class="form-control" name="phone" id="phone" value="{{$setting->phone}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="email">{{__('page.Email')}}</label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{$setting->email}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="site_url">{{__('page.Website')}}</label>
                                        <input type="text" id="site_url" name="site_url" value="{{$setting->site_url}}" class="form-control" placeholder="www.example.com" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="ios_app_url">{{__('page.IOS_App_Url')}}</label>
                                        <input type="text" id="ios_app_url" name="ios_app_url" value="{{$setting->ios_app_url}}" class="form-control" placeholder="Name" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="ios_version">{{__('page.IOS_Version')}}</label>
                                        <input type="text" id="ios_version" name="ios_version" value="{{$setting->ios_version}}" class="form-control" placeholder="Name" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="android_version">{{__('page.Android_Version')}}</label>
                                        <input type="text" id="android_version" name="android_version" value="{{$setting->android_version}}" class="form-control" placeholder="Name" />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="ios_app_url_driver">{{__('page.IOS_App_Url_driver')}}</label>
                                        <input type="text" id="ios_app_url_driver" name="ios_app_url_driver" value="{{$setting->ios_app_url_driver}}" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="ios_version_driver">{{__('page.IOS_Version_driver')}}</label>
                                        <input type="text" id="ios_version_driver" name="ios_version_driver" value="{{$setting->ios_version_driver}}" class="form-control"  />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="android_version_driver">{{__('page.Android_Version_driver')}}</label>
                                        <input type="text" id="android_version_driver" name="android_version_driver" value="{{$setting->android_version_driver}}" class="form-control"  />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="arabic_currency">{{__('page.Currency_Arabic')}}</label>
                                        <input type="text" id="arabic_currency" name="arabic_currency" value="{{$setting->arabic_currency}}" class="form-control" placeholder="Arabic Currency" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="english_currency">{{__('page.Currency_English')}}</label>
                                        <input type="text" id="english_currency" name="english_currency" value="{{$setting->english_currency}}" class="form-control" placeholder="English Currency" />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="logo">{{__('page.logo')}}</label>
                                        <input type="file" id="logo" name="logo" value="{{$setting->logo}}" class="form-control"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.Social_Media')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="facebook">{{__('page.Facebook')}}</label>
                                        <input type="text" class="form-control" name="facebook" id="facebook" value="{{$setting->facebook}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="twitter_link">{{__('page.Twitter')}}</label>
                                        <input type="text" class="form-control" id="twitter_link" name="twitter_link" value="{{$setting->twitter_link}}"  />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="instagram_link">{{__('page.Instagram')}}</label>
                                        <input type="text" class="form-control" id="instagram_link" name="instagram_link" value="{{$setting->instagram_link}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="youtube_link">{{__('page.Youtube')}}</label>
                                        <input type="text" class="form-control" id="youtube_link"  name="youtube_link" value="{{$setting->youtube_link}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="helperText">{{__('page.Whatsapp_Number')}}</label>
                                        <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{$setting->whatsapp_number}}" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="sos_number">{{__('label.sos_number')}}</label>
                                        <input type="text" id="sos_number" name="sos_number" value="{{$setting->sos_number}}" class="form-control"  />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="sos_number_2">{{__('label.sos_number_2')}}</label>
                                        <input type="text" id="sos_number_2" name="sos_number_2" value="{{$setting->sos_number_2}}" class="form-control"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.Trip_Settings')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="price_min_stop">{{__('page.Price_of_minute_stopping')}}</label>
                                        <input type="text" class="form-control" id="price_min_stop" name="price_min_stop" value="{{$setting->price_min_stop}}"  />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="price_min">{{__('page.Price_of_minute')}}</label>
                                        <input type="text" class="form-control" id="price_min" name="price_min" value="{{$setting->price_min}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="price_open">{{__('page.Open_Price')}}</label>
                                        <input type="text" class="form-control" id="price_open"  name="price_open" value="{{$setting->price_open}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_refresh_counter">{{__('page.time_to_refresh_counter')}}</label>
                                        <input type="text" class="form-control" id="time_to_refresh_counter"  name="time_to_refresh_counter" value="{{$setting->time_to_refresh_counter}}" />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="compensation_driver_per_kilo">{{'مقدار تعويض الكابتن للكيلومتر'}}</label>
                                        <input type="text" class="form-control" id="compensation_driver_per_kilo"  name="compensation_driver_per_kilo" value="{{$setting->compensation_driver_per_kilo}}" />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_refresh_counter">{{__('page.driver_accept_time_out')}}</label>
                                        <input type="text" class="form-control" id="driver_accept_time_out"  name="driver_accept_time_out" value="{{$setting->driver_accept_time_out}}" />
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_update_driver_counter">{{__('label.time_to_update_driver_counter')}}</label>
                                        <input type="number" class="form-control" id="time_to_update_driver_counter"  name="time_to_update_driver_counter" value="{{$setting->time_to_update_driver_counter}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_update_customer_counter">{{__('label.time_to_update_customer_counter')}}</label>
                                        <input type="number" class="form-control" id="time_to_update_customer_counter"  name="time_to_update_customer_counter" value="{{$setting->time_to_update_customer_counter}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_request_driver_location">{{__('label.time_to_request_driver_location')}}</label>
                                        <input type="number" class="form-control" id="time_to_request_driver_location"  name="time_to_request_driver_location" value="{{$setting->time_to_request_driver_location}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_request_customer_location">{{__('label.time_to_request_customer_location')}}</label>
                                        <input type="number" class="form-control" id="time_to_request_customer_location"  name="time_to_request_customer_location" value="{{$setting->time_to_request_customer_location}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_update_marker_on_the_map">زمن تحديث مكان الكابتن بعد بدء الرحلة</label>
                                        <select class=" form-control " name="time_to_update_marker_on_the_map"  id="time_to_update_marker_on_the_map">
                                            @for($i=1;$i<=20;$i++)
                                                @if($setting->request_distance_from_application == $i)
                                                <option value="{{$i}}" selected>{{$i}}</option>
                                                @else
                                                <option value="{{$i}}" >{{$i}}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="refresh_captin_marker_when_waiting">زمن  تحديث مكان الكابتن عند انتظار العميل </label>
                                        <input type="number" class="form-control" id="refresh_captin_marker_when_waiting"  name="refresh_captin_marker_when_waiting" value="{{$setting->refresh_captin_marker_when_waiting}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="schedule_trip_discount">{{'قيمة الخصم على الرحلة المجدولة'}}</label>
                                        <input type="number" class="form-control" id="schedule_trip_discount"  name="schedule_trip_discount" value="{{$tSetting->schedule_trip_discount}}" />
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="factory_price">{{__('setting.distance_factory')}}</label>
                                        <input type="text" class="form-control" id="factory_price"  name="factory_price" value="{{$tSetting->factory_price}}" />
                                    </div>
                                </div>


                                <div class="col-xl-12 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="switch-label" for="price_open">{{__('page.Add_Google_cost_to_invoice')}}</label><br>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="add_google_cost" @if ($setting->add_google_cost==1){{"checked"}} @else {{""}} @endif />
                                            <label class="custom-control-label" for="customSwitch1">{{__('page.Toggle_this_switch_element')}}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.Points_Settings')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="switch-label" for="price_open">{{__('page.enable_points_in_bills')}}</label><br>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="is_enabled_points" name="is_enabled_points" @if ($setting->is_enabled_points==1){{"checked"}} @else {{""}} @endif />
                                            <label class="custom-control-label" for="is_enabled_points">{{__('page.Toggle_this_switch_element')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.finance_Settings')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label  for="discount_driver">{{__('page.discount_amount_drivers')}}</label>
                                        <input type="number" class="form-control" id="discount_driver" name="discount_driver" value="{{$setting->discount_driver}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label  for="company_percentage">(%) {{__('page.company_percentage')}}</label>
                                        <input type="number" class="form-control" id="company_percentage" name="company_percentage" value="{{$setting->company_percentage}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label  for="min_amount_to_stop_driver">{{__('setting.min_amount_to_stop_driver')}}</label>
                                        <input type="number" class="form-control" id="min_amount_to_stop_driver" name="min_amount_to_stop_driver" value="{{$setting->min_amount_to_stop_driver}}" min="0" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label  for="long_distance_start_after">{{__('setting.long_distance_start_after')}}</label>
                                        <input type="number" class="form-control" id="long_distance_start_after" name="long_distance_start_after" value="{{$setting->long_distance_start_after}}" min="0" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label  for="gift_captain_girl">{{__('setting.gift_captain_girl')}}</label>
                                        <input type="number" class="form-control" id="gift_captain_girl" name="gift_captain_girl" value="{{$tSetting->gift_captain_girl}}" min="0" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label  for="gift_captain_girl">{{__('setting.distance_compensation_scheduled')}}</label>
                                        <input type="number" class="form-control" id="distance_compensation_scheduled" name="distance_compensation_scheduled" value="{{$tSetting->distance_compensation_scheduled}}" min="0" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label  for="gift_captain_male">{{__('setting.gift_captain_male')}}</label>
                                        <input type="number" class="form-control" id="gift_captain_male" name="gift_captain_male" value="{{$tSetting->gift_captain_male}}" min="0" />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="switch-label" for="price_open">{{__('page.enable_payment')}}</label><br>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="enable_payment" name="enable_payment" @if ($setting->enable_payment==1){{"checked"}} @else {{""}} @endif />
                                            <label class="custom-control-label" for="enable_payment">{{__('page.Toggle_this_switch_element')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('page.enable_PIN')}}</h4>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="switch-label" for="price_open">{{__('page.enable_PIN')}}</label><br>
                                        <div class="custom-control custom-switch custom-control-inline">
                                            <input type="checkbox" class="custom-control-input" id="enable_PIN" name="enable_PIN" @if ($setting->enable_PIN==1){{"checked"}} @else {{""}} @endif />
                                            <label class="custom-control-label" for="enable_PIN">{{__('page.Toggle_this_switch_element')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{'الدوائر'}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="first_circle_radius">{{'نصف قطر الدائرة الأولى'}}</label>
                                        <input type="number" class="form-control" id="first_circle_radius" name="first_circle_radius" value="{{$setting->first_circle_radius}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="other_circles_ratio">{{'نسبة الدوائر الأخرى'}}</label>
                                        <input type="text" class="form-control" id="other_circles_ratio"  name="other_circles_ratio" value="{{$setting->other_circles_ratio}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="time_to_move_to_other_circle">{{__('setting.time_to_move_to_other_circle')}}</label>
                                        <input type="number" class="form-control" id="time_to_move_to_other_circle"  name="time_to_move_to_other_circle" value="{{$setting->time_to_move_to_other_circle}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('setting.messages')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="connected_message_arabic">{{__('setting.connected_message_arabic')}}</label>
                                        <input type="text" class="form-control" id="connected_message_arabic" name="connected_message_arabic" value="{{$setting->connected_message_arabic}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="connected_message_english">{{__('setting.connected_message_english')}}</label>
                                        <input type="text" class="form-control" id="connected_message_english"  name="connected_message_english" value="{{$setting->connected_message_english}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="welcome_message_arabic">{{__('setting.welcome_message_arabic')}}</label>
                                        <input type="text" class="form-control" id="welcome_message_arabic" name="welcome_message_arabic" value="{{$setting->welcome_message_arabic}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="welcome_message_english">{{__('setting.welcome_message_english')}}</label>
                                        <input type="text" class="form-control" id="welcome_message_english"  name="welcome_message_english" value="{{$setting->welcome_message_english}}" />
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="bye_message_arabic">{{__('setting.bye_message_arabic')}}</label>
                                        <input type="text" class="form-control" id="bye_message_arabic" name="bye_message_arabic" value="{{$setting->bye_message_arabic}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="bye_message_english">{{__('setting.bye_message_english')}}</label>
                                        <input type="text" class="form-control" id="bye_message_english"  name="bye_message_english" value="{{$setting->bye_message_english}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="alert_balance_arabic">{{'رسالة نفاذ الرصيد'}}</label>
                                        <input type="text" class="form-control" id="alert_balance_arabic" name="alert_balance_arabic" value="{{$setting->alert_balance_arabic}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="alert_balance_english">{{'رسالة نفاذ الرصيد انكليزي'}}</label>
                                        <input type="text" class="form-control" id="alert_balance_english"  name="alert_balance_english" value="{{$setting->alert_balance_english}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="registration_welcome_msg_ar">{{__('setting.registration_welcome_msg_ar')}}</label>
                                        <input type="text" class="form-control" id="registration_welcome_msg_ar" name="registration_welcome_msg_ar" value="{{$setting->registration_welcome_msg_ar}}" />
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 mb-1">
                                    <div class="form-group">
                                        <label for="registration_welcome_msg_en">{{__('setting.registration_welcome_msg_en')}}</label>
                                        <input type="text" class="form-control" id="registration_welcome_msg_en"  name="registration_welcome_msg_en" value="{{$setting->registration_welcome_msg_en}}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
{{--                <div class="col-md-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header">--}}
{{--                            <h4 class="card-title">{{__('نقل الأثاث')}}</h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-xl-4 col-md-6 col-12 mb-1">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="switch-label" for="is_show_furniture">{{__('تفعيل نقل الأثاث')}}</label><br>--}}
{{--                                        <div class="custom-control custom-switch custom-control-inline">--}}
{{--                                            <input type="checkbox" class="custom-control-input" id="is_show_furniture" name="is_show_furniture" @if ($setting->is_show_furniture==1){{"checked"}} @else {{""}} @endif />--}}
{{--                                            <label class="custom-control-label" for="is_show_furniture">{{__('page.Toggle_this_switch_element')}}</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-sm-9 ">

                    @if(in_array('edit_setting',$permissionsNames) || $isAdmin)
                    <button type="submit" class="btn btn-primary mr-1 mb-1">{{__('setting.Save')}}</button>
                    @endif
                    <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url("admin/home") }}">{{__('menus.back')}} </a>
                </div>
            </div>
            </form>
        </section>
        <!-- Basic Inputs end -->
    </div>
@endsection
@push('confirm_maintenance')
    <script>
        // Confirm Text
        $('.confirm_maintenance').on('click', function (event) {
            //confirmText.on('click', function () {
            event.preventDefault();

            const url = $(this).attr('href');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText:'إلغاء',
                confirmButtonText: 'نعم، قم بالتفعيل',
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
                        title: 'تم التفعيل!',
                        text: 'تم تفعيل وضع الصيانة',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
            });
        });

    </script>
@endpush
