<!DOCTYPE html>

<html class="loading semi-dark-layout" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="semi-dark-layout" data-textdirection="ltr" >
  <!-- BEGIN: Head-->
  <head>
      @php $locale = app()->getLocale();
       // echo session()->get('locale')."-";
 @endphp
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin taxi app, taxi app friends">
    <meta name="author" content="PIXINVENT">
    <title>{{$setting->title}}</title>
    <link rel="apple-touch-icon" href="{{ asset('admin/app-assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('admin/app-assets/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
      @if($locale=='en')
              <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/vendors.min.css')}}">
              @stack('datepicker_header')
              <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/forms/select/select2.min.css')}}">
              @stack('datatableheader')

            <!-- BEGIN: Theme CSS-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/bootstrap.min.css')}}">
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/bootstrap-extended.min.css')}}">
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/colors.min.css')}}">
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/components.min.css')}}">
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/themes/semi-dark-layout.min.css')}}">
            <!-- BEGIN: Page CSS-->
            @stack('datepicker_header2')
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/core/menu/menu-types/vertical-menu.min.css')}}">
            @stack('view-page-css')
            <!-- END: Page CSS-->
            <!-- BEGIN: Custom CSS-->
              <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/style.css')}}">
            <!-- END: Custom CSS-->
      @else
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/vendors-rtl.min.css')}}">
          @stack('datepicker_header')
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/forms/select/select2.min.css')}}">
          @stack('datatableheader')
          <!-- BEGIN: Theme CSS-->
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/bootstrap.min.css')}}">
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/bootstrap-extended.min.css')}}">
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/colors.min.css')}}">
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/components.min.css')}}">
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/themes/semi-dark-layout.min.css')}}">
          <!-- BEGIN: Page CSS-->
          @stack('datepicker_header2')
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/core/menu/menu-types/vertical-menu.min.css')}}">
          @stack('view-page-css')
            <!-- END: Page CSS-->
          <!-- BEGIN: Custom CSS-->
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/custom-rtl.min.css')}}">
          <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/style-rtl.css')}}">
          <!-- END: Custom CSS-->
      @endif

<style>
    .alert-body{
        font-size: 16px;
    }
</style>
@php
    //echo $locale;
// if mobile
    $isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"));

    if($isMob){
    echo "<style>
        html .content{
            margin-right: 0px;
        }
    </style>";
    }
@endphp
  </head>
  <!-- END: Head-->
  @php

          $permissionsNames = array();
          $permissions = Auth::guard('admin')->user()->getAllPermissions();
          foreach ($permissions as $p){
          $permissionsNames[] = $p->name;
          }
        // var_dump($permissionsNames);
  @endphp

  <!-- BEGIN: Body-->

  <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">
    <!-- BEGIN: Header-->
    @php //$isAdmin = Auth::guard('admin')->user()?->role('Super-Admin');
        //$isAdmin = Auth::guard('admin')->user()->hasRole('Super-Admin');

    @endphp
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
      <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
          <ul class="nav navbar-nav d-xl-none">
            <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
          </ul>
            <ul class="nav navbar-nav bookmark-icons">
                <li class="nav-item d-none d-lg-block"><a class="btn btn-gradient-warning mr-2" href="#" title="refresh" onClick="window.location.reload();">{{__('menus.refresh')}}</a></li>
                <li class="nav-item d-none d-lg-block"> <a type="button" class="btn btn-info mr-1 mb-1 " href="{{url()->previous() }}">{{__('menus.back')}} </a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">
          <li class="nav-item dropdown dropdown-language">
              @if($locale=='en')
                    <a class="nav-link dropdown-toggle" id="dropdown-flag" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-us"></i><span class="selected-language">English</span></a>
              @else
                  <a class="nav-link dropdown-toggle" id="dropdown-flag" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-sy"></i><span class="selected-language">عربي</span></a>
              @endif
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">
                <a class="dropdown-item" href="{{url('change-language/en')}}" data-language="en"><i class="flag-icon flag-icon-us"></i> English</a>
                <a class="dropdown-item" href="{{url('change-language/ar')}}" data-language="sa"><i class="flag-icon flag-icon-sy"></i> عربي</a>
            </div>
          </li>


          <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="user-nav d-sm-flex d-none">
                  <span class="user-name font-weight-bolder"></span>
                  <span class="user-status">{{Auth::guard('admin')->user()->name}}</span>
              </div>
                  <span class="avatar"><img class="round" src="{{ asset('admin/app-assets/images/portrait/small/avatar-s-11.jpg')}}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
{{--                <a class="dropdown-item" href="page-profile.html"><i class="mr-50" data-feather="user"></i> Profile</a>--}}

                <a class="dropdown-item" href="{{ url('admin/change_password') }}"> <i data-feather='lock'></i> {{__('label.change_password')}}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="mr-50" data-feather="power"></i>{{ __('auth.Logout') }}
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="get" class="d-none">
                    @csrf
                </form>

            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
      <div class="navbar-header" style="height: 50px">
        <ul class="nav navbar-nav flex-row">
          <li class="nav-item mr-auto">
              <a class="navbar-brand" href="#">

              <h2 class="brand-text"></h2></a>
          </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
      </div>
      <div class="shadow-bottom"></div>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
          @if($isAdmin)
          <li class="  nav-item"><a class="d-flex align-items-center" href="{{url('admin/home')}}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">{{__('menus.Dashboard')}}</span><span class="badge badge-light-warning badge-pill ml-auto mr-1"></span></a>
          </li>
          @endif
          <li class=" navigation-header"><span data-i18n="Apps &amp; Pages"> {{__('menus.Modules')}}</span><i data-feather="more-horizontal"></i>
          </li>

          @if(in_array('drivers',$permissionsNames) || $isAdmin || in_array('renew balance',$permissionsNames))
          <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="user-check"></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.Drivers')}}</span></a>
              <ul class="menu-content">
                  @if(in_array('drivers',$permissionsNames) || $isAdmin)
                  <li>
                      <a class="d-flex align-items-center" href="{{url('admin/drivers')}}"><i data-feather="circle"></i>
                          <span class="menu-title text-truncate" data-i18n="Email">{{__('menus.Drivers')}}</span></a>
                  </li>
                  @endif
                  @if(in_array('drivers',$permissionsNames) || $isAdmin)
                  <li>
                      <a class="d-flex align-items-center" href="{{url('admin/drivers/money')}}"><i data-feather="circle"></i>
                          <span class="menu-title text-truncate" data-i18n="Email">{{__('menus.SumOfMoney')}}</span></a>
                  </li>
                  @endif
                  @if(in_array('search driver',$permissionsNames) || $isAdmin)
                  <li><a class="d-flex align-items-center" href="{{url('admin/search-drivers')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Details">{{__('menus.Search')}}</span></a>
                  </li>
                  @endif
                  @if(in_array('renew balance',$permissionsNames) || $isAdmin)
                  <li><a class="d-flex align-items-center" href="{{url('renew_balance_form')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Details">{{__('label.renew_balance')}}</span></a>
                  </li>
                 @endif
              </ul>
          </li>
          @endif

          @if(in_array('users',$permissionsNames) || $isAdmin)
          <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="{{__('menus.Users')}}">{{__('menus.Users')}}</span></a>
              <ul class="menu-content">
                  <li>
                      <a class="d-flex align-items-center" href="{{url('admin/users')}}"><i data-feather="circle"></i>
                          <span class="menu-title text-truncate" data-i18n="Email">{{__('menus.Users')}}</span></a>
                  </li>
                  <li>
                      <a class="d-flex align-items-center" href="{{url('admin/search-users')}}"><i data-feather="circle"></i>
                          <span class="menu-item text-truncate" data-i18n="Details">{{__('menus.Search')}}</span></a>
                  </li>
              </ul>
          </li>
          @endif
            @if(in_array('cars',$permissionsNames) || $isAdmin)
            <li class="nav-item"><a class="d-flex align-items-center" href="#"><i data-feather='hard-drive'></i><span class="menu-title text-truncate" data-i18n="{{__('menus.Cars')}}">{{__('menus.Cars')}}</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{url('admin/cars')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="{{__('menus.Car_list')}}">{{__('menus.Car_list')}}</span></a>
                    </li>
                    @if(in_array('Car type',$permissionsNames) || $isAdmin)
                    <li><a class="d-flex align-items-center" href="{{url('admin/car-types')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="{{__('menus.Car_types')}}">{{__('menus.Car_types')}}</span></a>
                    </li>
                    @endif
                    <li><a class="d-flex align-items-center" href="{{url('admin/car-models')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="{{__('menus.Car_Models')}}">{{__('menus.Car_Models')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/colors')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="{{__('menus.Colors')}}">{{__('menus.Colors')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/brands')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="{{__('menus.Colors')}}">{{__('menus.Brands')}}</span></a>
                    </li>
                </ul>
            </li>
            @endif
            @if(in_array('trips',$permissionsNames) || $isAdmin  || in_array('search trips',$permissionsNames) )
            <li class="nav-item"><a class="d-flex align-items-center" href="#"><i data-feather='map-pin'></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.Trips')}}</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{url('admin/trips')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.Trip_list')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/canceled_trips')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Details">{{__('menus.Cancelled_trips')}}</span></a>
                    </li>
                    @if(in_array('search trips',$permissionsNames) || $isAdmin)
                    <li><a class="d-flex align-items-center" href="{{url('admin/trips/search')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Details">{{__('menus.Search')}}</span></a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if(in_array('companies',$permissionsNames) || $isAdmin)
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="{{url('admin/companies')}}"><i data-feather='framer'></i>
                    <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.companies')}}</span></a>
            </li>
            @endif
            @if(in_array('slider',$permissionsNames) || $isAdmin)
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="{{url('admin/slider')}}"><i data-feather="image"></i>
                    <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.Slider')}}</span></a>
            </li>
            @endif
              @if(in_array('driver-balance',$permissionsNames) || $isAdmin)
              <li class=" nav-item">
                  <a class="d-flex align-items-center" href="{{url('admin/driver-balance')}}"><i data-feather='dollar-sign'></i>
                      <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.driver_balance')}}</span></a>
              </li>
              @endif
              @if(in_array('driver-compensation',$permissionsNames) || $isAdmin)
              <li class=" nav-item">
                  <a class="d-flex align-items-center" href="{{url('admin/driver-compensation')}}"><i data-feather='dollar-sign'></i>
                      <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.driver_compensation')}}</span></a>
              </li>
              @endif
            @if(in_array('Cancel reasons',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="shopping-cart"></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.Reasons_list')}}</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{url('admin/cancel_reasons')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.Cancel_Reasons')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/feedback_reasons')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.feed_Reasons')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/alerts')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.alerts')}}</span></a>
                    </li>
                </ul>
            </li>
            @endif

            @if(in_array('Offers',$permissionsNames) || $isAdmin)
              <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="credit-card"></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.Offers')}}</span></a>
                <ul class="menu-content">
                  <li><a class="d-flex align-items-center" href="{{url('admin/offers')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.Offers_list')}}</span></a>
                  </li>
                </ul>
              </li>
            @endif

            @if(in_array('Notifications',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather='bell'></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.Notifications')}}</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{url('admin/notifications')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.users_notifications')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/drivers_notifications')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.drivers_notifications')}}</span></a>
                    </li>
                </ul>
            </li>
            @endif
            @if(in_array('Faqs',$permissionsNames) || $isAdmin)
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="{{url('admin/faqs/')}}"><i data-feather="help-circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.Faqs')}}</span></a>
            </li>
            @endif
{{--            @if(in_array('Send alerts',$permissionsNames) || $isAdmin)--}}
{{--            <li class=" nav-item">--}}
{{--                <a class="d-flex align-items-center" href="{{url('admin/send-alerts/')}}"><i data-feather='alert-triangle'></i>--}}
{{--                    <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.Send_warnings')}}</span></a>--}}
{{--            </li>--}}
{{--            @endif--}}
            @if(in_array('Permissions',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="credit-card"></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.roles')}}</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{url('admin/roles')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.roles_')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/users_panel')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Details">{{__('menus.users_panel')}}</span></a>
                    </li>
                </ul>
            </li>
            @endif

            @if(in_array('SMS',$permissionsNames) || $isAdmin)
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="{{url('admin/send-sms/')}}"><i data-feather='alert-triangle'></i>
                    <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.Send_sms')}}</span></a>
            </li>
            @endif

            @if(in_array('slider',$permissionsNames) || $isAdmin)
            <li class="nav-item"><a class="d-flex align-items-center" href="#"><i data-feather='map-pin'></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.cities_and_regions')}}</span></a>
                <ul class="menu-content">
                    <li >
                        <a class="d-flex align-items-center" href="{{url('admin/borders/')}}"><i data-feather='map'></i>
                            <span class="menu-title text-truncate" data-i18n="Chat">{{__('page.available_areas')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/cities')}}"><i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.cities')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/regions')}}"><i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="Details">{{__('menus.regions')}}</span></a>
                    </li>
                </ul>
            </li>
            @endif

            @if(in_array('Complaints',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{url('admin/complaints')}}"><i data-feather='alert-circle'></i><span class="menu-title text-truncate" data-i18n="Settings">{{__('menus.complaints')}}</span></a>
            @endif

            @if(in_array('Settings',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{url('admin/settings/edit')}}"><i data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Settings">{{__('menus.Settings')}}</span></a>
            @endif

            @if(in_array('farway_regions',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{url('admin/farway_regions/')}}"><i data-feather='anchor'></i><span class="menu-title text-truncate" data-i18n="Settings">{{__('menus.farway_regions')}}</span></a>
            @endif

           @if(in_array('invoices',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{url('admin/invoices/')}}"><i data-feather='check-square'></i><span class="menu-title text-truncate" data-i18n="Settings">{{__('menus.invoices')}}</span></a>
              @endif
            @if(in_array('KPIS',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather='bar-chart'></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.KPI')}}</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{url('admin/kpi_users')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.Users')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/kpi_drivers')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.Drivers')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('admin/kpi_trips')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.Trips')}}</span></a>
                    </li>
                </ul>
            </li>
            @endif

            @if(in_array('Log',$permissionsNames) || $isAdmin)
            <li class=" nav-item"><a class="d-flex align-items-center" href="{{url('admin/log')}}"><i data-feather='file-text'></i><span class="menu-title text-truncate" data-i18n="Settings">{{__('menus.log')}}</span></a>
            @endif

            @if( in_array('verification_codes',$permissionsNames) || $isAdmin)
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="{{url('admin/verification_codes')}}"><i data-feather='code'></i>
                    <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.verification_codes')}}</span></a>
            </li>
            @endif

          @if( in_array('pos',$permissionsNames) || $isAdmin)
              <li class=" nav-item">
                  <a class="d-flex align-items-center" href="{{url('admin/search-pos')}}"><i data-feather='code'></i>
                      <span class="menu-title text-truncate" data-i18n="Chat">{{__('menus.pos')}}</span></a>
              </li>
          @endif

            @if(in_array('Privacy Policy',$permissionsNames) || $isAdmin)
              <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather='bar-chart'></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{__('menus.policy_who')}}</span></a>
                  <ul class="menu-content">
                      <li><a class="d-flex align-items-center" href="{{url('admin/privacy_policy')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.policy')}}</span></a>
                      </li>
                      <li><a class="d-flex align-items-center" href="{{url('admin/who_we_are')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.who')}}</span></a>
                      </li>
                  </ul>
              </li>
           @endif
          @if(in_array('company',$permissionsNames) )
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather='bar-chart'></i><span class="menu-title text-truncate" data-i18n="eCommerce">{{Auth::guard('admin')->user()->name}}</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{url('company/edit/')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.company_profile')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('company/employees')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.employees')}}</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{{url('company/trips')}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Shop">{{__('menus.Trips')}}</span></a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
      </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content " id="content">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper container-xxl p-0">

        @yield('content')

      </div>
    </div>
    <!-- END: Content-->
  <!-- Footer -->

</div>
    </div>
    <!-- End: Customizer-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
      <p class="clearfix mb-0" style="margin-right: 250px"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT  &copy; 2022<a class="ml-25" href="#" target="_blank">BIS</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('admin/app-assets/vendors/js/vendors.min.js')}}"></script>

    @stack('datepicker')
    <!-- Sweet alert delete -->

    @stack('datatablefooter')
    <!-- BEGIN Vendor JS-->
    <script  src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        //Confirm Text
        $(document).on('click', '.confirm-text', function(){
            event.preventDefault();
            //console.log(345);
            const url = $(this).attr('href');
            //alert("fdf");
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "غير قابل للاسترجاع",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText:'إلغاء',
                confirmButtonText: 'نعم، قم بالحذف!',
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
                        title: 'تم الحذف!',
                        text: 'تم حذف عنصر من القائمة',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
            });
        });
    </script>

    @stack('confirm_maintenance')
    @stack('select2')
    @stack('side_modal')
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('admin/app-assets/js/core/app-menu.min.js')}}"></script>
    <script src="{{ asset('admin/app-assets/js/core/app.min.js')}}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/customizer.min.js')}}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/pages/app-user-list.min.js')}}"></script>


    @stack('richtext')



    @stack('datepicker2')

    <!-- BEGIN: Page JS-->
{{--    <script src="{{ asset('admin/app-assets/js/scripts/pages/app-user-list.js')}}"></script>--}}
{{--    <script src="{{ asset('admin/app-assets/js/scripts/extensions/ext-component-sweet-alerts.min.js')}}"></script>--}}
    <!-- END: Page JS-->
    <script>
      $(window).on('load',  function(){
        if (feather) {
          feather.replace({ width: 14, height: 14 });
        }
      })
    </script>
    <script>
        $(window).on('load',  function(){
            var checkvalue= window.location.pathname;
            //alert(checkvalue);
            var b = document.querySelector(".active");
            if (b) b.classList.remove("active");
            //console.log(b);
            var cur = $("a[href*='" + location.pathname + "']");
            console.log(cur);
            cur.parent().addClass("active");
            // $("li a").each(function(){
            //
            //     var hrefvalue="?" + $(this).attr('href').split("?")[0];
            //     console.log(hrefvalue)
            //     if(hrefvalue== checkvalue) { $(this).addClass("active");}
            // });
        });
    </script>

  @stack('ajax')
  @stack('form_validation')

  </body>
  <!-- END: Body-->
</html>
