<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="Kempt | Multipurpose Creative HTML Template" />
    <meta name="keywords" content="creative,multipurpose,business,photography,fashion,parallax,portfolio,agency" />
    <meta name="author" content="ThemeMascot" />

    <!-- Page Title -->
    <title>{{$setting->title}} | {{$title}}</title>

    <!-- Favicon and Touch Icons -->
    <link href="images/favicon.png" rel="shortcut icon" type="image/png">
    <link href="images/apple-touch-icon.png" rel="icon">
    <link href="images/apple-touch-icon-72x72.png" rel="icon" sizes="72x72">
    <link href="images/apple-touch-icon-114x114.png" rel="icon" sizes="114x114">
    <link href="images/apple-touch-icon-144x144.png" rel="icon" sizes="144x144">

    <!-- Stylesheet -->
    <link href="{{ asset('website/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('website/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{ asset('website/css/jquery-ui.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('website/css/animate.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('website/css/css-plugin-collections.css')}}" rel="stylesheet"/>
    <!-- CSS | menuzord megamenu skins -->
    <link id="menuzord-menu-skins" href="{{ asset('website/css/menuzord-skins/menuzord-default.css')}}" rel="stylesheet"/>
    <!-- CSS | Main style file -->
    <link href="{{ asset('website/css/style-main.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('website/css/style-main-rtl.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('website/css/style-main-rtl-extra.css')}}" rel="stylesheet" type="text/css">
    <!-- CSS | Preloader Styles -->
    <link href="{{ asset('website/css/preloader.css')}}" rel="stylesheet" type="text/css">
    <!-- CSS | Custom Margin Padding Collection -->
    <link href="{{ asset('website/css/custom-bootstrap-margin-padding.css')}}" rel="stylesheet" type="text/css">
    <!-- CSS | Responsive media queries -->
    <link href="{{ asset('website/css/responsive.css')}}" rel="stylesheet" type="text/css">
    <!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
    <!-- <link href="{{ asset('website/css/style.css')}}" rel="stylesheet" type="text/css"> -->

    <!-- Revolution Slider 5.x CSS settings -->
    <link  href="{{ asset('website/js/revolution-slider/css/settings.css')}}" rel="stylesheet" type="text/css"/>
    <link  href="{{ asset('website/js/revolution-slider/css/layers.css')}}" rel="stylesheet" type="text/css"/>
    <link  href="{{ asset('website/js/revolution-slider/css/navigation.css')}}" rel="stylesheet" type="text/css"/>

    <!-- CSS | Theme Color -->
    <link href="{{ asset('website/css/colors/theme-skin-feroza.css')}}" rel="stylesheet" type="text/css">

    <!-- external javascripts -->
    <script src="{{ asset('website/js/jquery-2.2.4.min.js')}}"></script>
    <script src="{{ asset('website/js/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('website/js/bootstrap.min.js')}}"></script>
    <!-- JS | jquery plugin collection for this theme -->
    <script src="{{ asset('website/js/jquery-plugin-collection.js')}}"></script>

    <!-- Revolution Slider 5.x SCRIPTS -->
    <script src="{{ asset('website/js/revolution-slider/js/jquery.themepunch.tools.min.js')}}"></script>
    <script src="{{ asset('website/js/revolution-slider/js/jquery.themepunch.revolution.min.js')}}"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .menuzord-menu >li{
            float:right !important;
        }
    </style>
</head>
<body class="">
<div id="wrapper" class="clearfix">
    <!-- preloader -->
    <div id="preloader">
        <div id="spinner" class="spinner large-icon">
            <img alt="" src="images/preloaders/4.gif">
        </div>
        <div id="disable-preloader" class="btn btn-default btn-sm">Disable Preloader</div>
    </div>

    <!-- Header -->
    <header id="header" class="header">
        <div class="header-nav navbar-fixed-top header-dark navbar-white navbar-transparent navbar-sticky-animated animated-active border-bottom-transparent">
            <div class="header-nav-wrapper">
                <div class="container">
                    <nav id="menuzord-right" class="menuzord orange">
                        <a class="menuzord-brand pull-left flip mt-10" href="javascript:void(0)">
                            <img src="{{url('storage/'.$setting->logo)}}">
                        </a>
                        <ul class="menuzord-menu dark pull-left">
                            <li class="active"><a href="{{url('/')}}">{{__('menus.Home')}}</a>
                            </li>
                            <li class=""><a href="{{url('privacy')}}">{{__('menus.policy')}}</a>

                            </li>
                            <li class=""><a href="{{url('contact')}}">{{__('menus.contact')}}</a>

                            </li>
{{--                            <li><a href="#home">Languages</a>--}}
{{--                                <ul class="dropdown">--}}
{{--                                    <li><a href="shop-category.html">Category</a></li>--}}
{{--                                    <li><a href="shop-category-sidebar.html">Category Sidebar</a></li>--}}

{{--                                </ul>--}}
{{--                            </li>--}}

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Start main-content -->
    <div class="main-content">
        @yield('content')

        <!-- end main-content -->
    </div>
    <!-- Footer -->
    <footer id="footer" class="divider bg-black-222">
        <div class="container pt-70 pb-40">
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="widget dark">
                        <h3 class="text-theme-colored text-uppercase font-25 font-weight-700 line-height-1 mt-0">{{$setting->title}}</h3>
                        <span class="text-gray-darkgray">رؤيتنا:</span>
                        <p class="text-gray-darkgray">أن نكون ضمن الثلاث شركات الأوائل ونتميز في خدمات نقل الركاب وغيرها من الخدمات عبر

                            تطبيقنا الالكتروني ( تفضل ) خلال ثلاثة سنوات</p>
                        <ul class="list-inline mt-15">
                            <li class="m-0 pl-10 pr-10"> <i class="fa fa-phone font-weight-600 mr-5"></i> <a class="text-gray-darkgray" href="#">{{$setting->phone}}</a> </li>
                            <li class="m-0 pl-10 pr-10"> <i class="fa fa-envelope-o font-weight-600 mr-5"></i> <a class="text-gray-darkgray" href="#">{{$setting->email}}</a> </li>
                            <li class="m-0 pl-10 pr-10"> <i class="flaticon-global font-weight-600 mr-5"></i> <a class="text-gray-darkgray float-left" href="#">{{$setting->site_url}}</a> </li>
                        </ul>

                    </div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="widget dark">
                        <h5 class="widget-title line-bottom text-uppercase font-weight-500 font-14">Opening</h5>
                        <div class="categories">
                            <ul class="list angle-right list-border">
                                <li><a href="#"><span>7 Days - 24 Hours</span></a></li>

                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="footer-bottom bg-black-333">
            <div class="container pt-15 pb-10">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-gray sm-text-center font-13 mt-5">Copyright &copy;2023 Tikram Co. All Rights Reserved</p>
                    </div>
                    <div class="col-md-6 text-right sm-text-center">
                        <ul class="styled-icons icon-theme-colored clearfix">
                            <li><a href="{{$setting->facebook}}"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
</div>
<!-- end wrapper -->

<!-- Footer Scripts -->
<!-- JS | Custom script for all pages -->
<script src="{{ asset('website/js/custom.js')}}"></script>

<!-- SLIDER REVOLUTION 5.0 EXTENSIONS
      (Load Extensions only on Local File Systems !
       The following part can be removed on Server for On Demand Loading) -->
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.actions.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.migration.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.parallax.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('website/js/revolution-slider/js/extensions/revolution.extension.video.min.js')}}"></script>

</body>
</html>
