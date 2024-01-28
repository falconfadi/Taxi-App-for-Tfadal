@extends('layouts.website')

@section('content')
    <!-- Section: home --> <!--1500*996-->


    <section id="home">
        <!-- Slider Revolution Start -->
        <div class="rev_slider_wrapper">
            <div class="rev_slider" data-version="5.0">
                <ul>
                    <!-- SLIDE 1 -->
                    <li data-index="rs-1" data-transition="slidingoverlayhorizontal" data-slotamount="default" data-easein="default" data-easeout="default" data-masterspeed="default" data-thumb="{{url('website/images/slider/tikram-group.jpg')}}" data-rotate="0" data-saveperformance="off" data-title="Web Show" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{url('website/images/slider/tikram-group.jpg')}}"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-bgparallax="8" data-no-retina>
                        <!-- LAYERS -->
                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-resizeme text-center text-white font-raleway"
                             id="rs-1-layer-1"

                             data-x="['center']"
                             data-hoffset="['0']"
                             data-y="['middle']"
                             data-voffset="['-65']"
                             data-fontsize="['24']"
                             data-lineheight="['64']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1000"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index: 5; white-space: nowrap; font-weight:700;">تفضّل خيارك الأفضل
                        </div>

                        <!-- LAYER NR. 4 -->
                        <div class="tp-caption tp-resizeme"
                             id="rs-1-layer-4"

                             data-x="['center']"
                             data-hoffset="['0']"
                             data-y="['middle']"
                             data-voffset="['80']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1400"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index: 5; white-space: nowrap; letter-spacing:1px;"><a class="btn btn-default btn-lg btn-circled" href="{{url('contact')}}">{{__('menus.contact')}}</a>
                        </div>
                    </li>

                    <!-- SLIDE 2 -->
                    <li data-index="rs-2" data-transition="slidingoverlayhorizontal" data-slotamount="default" data-easein="default" data-easeout="default" data-masterspeed="default" data-thumb="{{url('website/images/slider/tfadal-girl.jpg')}}" data-rotate="0" data-saveperformance="off" data-title="Web Show" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{url('website/images/slider/tfadal-girl.jpg')}}"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-bgparallax="8" data-no-retina>
                        <!-- LAYERS -->

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-resizeme text-center text-white font-raleway"
                             id="rs-2-layer-1"

                             data-x="['center']"
                             data-hoffset="['0']"
                             data-y="['middle']"
                             data-voffset="['-65']"
                             data-fontsize="['24']"
                             data-lineheight="['64']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1000"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index: 5; white-space: nowrap; font-weight:700;">تلبية سريعة
                        </div>

                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption tp-resizeme"
                             id="rs-2-layer-3"

                             data-x="['center']"
                             data-hoffset="['0']"
                             data-y="['middle']"
                             data-voffset="['80']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1400"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index: 5; white-space: nowrap; letter-spacing:1px;"><a class="btn btn-default btn-lg btn-circled" href="{{url('contact')}}">{{__('menus.contact')}}</a>
                        </div>
                    </li>

                    <!-- SLIDE 3 -->
                    <li data-index="rs-3" data-transition="slidingoverlayhorizontal" data-slotamount="default" data-easein="default" data-easeout="default" data-masterspeed="default" data-thumb="{{url('website/images/slider/tfadal.jpg')}}" data-rotate="0" data-saveperformance="off" data-title="Web Show" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{url('website/images/slider/tfadal.jpg')}}"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-bgparallax="8" data-no-retina>
                        <!-- LAYERS -->

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-resizeme text-center text-white font-raleway"
                             id="rs-3-layer-1"

                             data-x="['center']"
                             data-hoffset="['0']"
                             data-y="['middle']"
                             data-voffset="['-65']"
                             data-fontsize="['24']"
                             data-lineheight="['64']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1000"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index: 5; white-space: nowrap; font-weight:700;">أمان ووثوقيّة
                        </div>

                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption tp-resizeme"
                             id="rs-3-layer-3"

                             data-x="['center']"
                             data-hoffset="['0']"
                             data-y="['middle']"
                             data-voffset="['80']"
                             data-width="none"
                             data-height="none"
                             data-whitespace="nowrap"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1400"
                             data-splitin="none"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="z-index: 5; white-space: nowrap; letter-spacing:1px;"><a class="btn btn-default btn-lg btn-circled" href="{{url('contact')}}">{{__('menus.contact')}}</a>
                        </div>
                    </li>

                </ul>
            </div><!-- end .rev_slider -->
        </div>
        <!-- end .rev_slider_wrapper -->
        <script>
            $(document).ready(function(e) {
                var revapi = $(".rev_slider").revolution({
                    sliderType:"standard",
                    jsFileLocation: "js/revolution-slider/js/",
                    sliderLayout: "auto",
                    dottedOverlay: "none",
                    delay: 5000,
                    navigation: {
                        keyboardNavigation: "off",
                        keyboard_direction: "horizontal",
                        mouseScrollNavigation: "off",
                        onHoverStop: "off",
                        touch: {
                            touchenabled: "on",
                            swipe_threshold: 75,
                            swipe_min_touches: 1,
                            swipe_direction: "horizontal",
                            drag_block_vertical: false
                        },
                        arrows: {
                            style: "gyges",
                            enable: true,
                            hide_onmobile: false,
                            hide_onleave: true,
                            hide_delay: 200,
                            hide_delay_mobile: 1200,
                            tmp: '',
                            left: {
                                h_align: "left",
                                v_align: "center",
                                h_offset: 0,
                                v_offset: 0
                            },
                            right: {
                                h_align: "right",
                                v_align: "center",
                                h_offset: 0,
                                v_offset: 0
                            }
                        },
                        bullets: {
                            enable: true,
                            hide_onmobile: true,
                            hide_under: 800,
                            style: "hebe",
                            hide_onleave: false,
                            direction: "horizontal",
                            h_align: "center",
                            v_align: "bottom",
                            h_offset: 0,
                            v_offset: 30,
                            space: 5,
                            tmp: '<span class="tp-bullet-image"></span><span class="tp-bullet-imageoverlay"></span><span class="tp-bullet-title"></span>'
                        }
                    },
                    responsiveLevels: [1240, 1024, 778],
                    visibilityLevels: [1240, 1024, 778],
                    gridwidth: [1170, 1024, 778, 480],
                    gridheight: [680, 768, 960, 720],
                    lazyType: "none",
                    parallax:"mouse",
                    parallaxBgFreeze:"off",
                    parallaxLevels:[2,3,4,5,6,7,8,9,10,1],
                    shadow: 0,
                    spinner: "off",
                    stopLoop: "on",
                    stopAfterLoops: 0,
                    stopAtSlide: -1,
                    shuffle: "off",
                    autoHeight: "off",
                    fullScreenAutoWidth: "off",
                    fullScreenAlignForce: "off",
                    fullScreenOffsetContainer: "",
                    fullScreenOffset: "0",
                    hideThumbsOnMobile: "off",
                    hideSliderAtLimit: 0,
                    hideCaptionAtLimit: 0,
                    hideAllCaptionAtLilmit: 0,
                    debugMode: false,
                    fallbacks: {
                        simplifyAll: "off",
                        nextSlideOnWindowFocus: "off",
                        disableFocusListener: false,
                    }
                });
            });
        </script>
        <!-- Slider Revolution Ends -->
    </section>
    <!-- Section: About -->
    <section id="about" class="bg-silver-light">
        <div class="container">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-uppercase line-bottom text-gray-darkgray pt-60 pt-sm-0">شركة <b>تكرم</b> المحدودة المسؤولية هي شركة مختصة بالتطبيقات الإلكترونية في محافظة دمشق وريفها </h6>
                        <h3 class="font-weight-500 font-30 font- mt-10"><span class="text-theme-colored"> تفضّل خيارك الأفضل</span> </h3>

                        <p>تسعى شركة تكرم لأن تصبح الخيار الأفضل لعملائها في مجال الخدمات الإلكترونية التي تلبي احتياجاتهم.</p>
                        <p>أطلقت شركة تكرم تطبيقها الأول تفضل للعمل في خدمتها المبتكرة في السوق وهي خدمة توصيل سيدات من خلال كباتن إناث فقط. </p>
                    </div>
                    <div class="col-md-6">
                        <div class="video-popup">
                            <a title="Video" data-lightbox-gallery="youtube-video" href="#">
                                <img class="img-responsive img-fullwidth" src="{{url('website/images/home-tfadal.jpg')}}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Services -->
    <section>
        <div class="container">
            <div class="section-title wow fadeInUp" data-wow-duration="1.2s">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <h3 class="text-uppercase font-weight-500 font-28 line-bottom-centered mt-0"> <span class="text-theme-colored">خدماتنا</span></h3>
                        <p class="text-gray-dimgray">التطبيق الأفضل لحجز رحلاتك والكثير من احتياجاتك في تطبيق واحد.</p>
                        <p class="text-gray-dimgray">تسعى شركة تكرم من خلال تطبيق تفضل إيصالك إلى وجهتك بأمان وسرعة وخصوصية في كل مرة تطلب بها تطبيق تفضل</p>
                    </div>
                </div>
            </div>
            <div class="section-content wow fadeInUp" data-wow-duration=".6s">
                <div class="row">
                    <div class="col-md-3">
                        <div class="icon-box text-center mb-sm-60">
                            <a href="#" class="icon icon-gray icon-bordered bg-hover-theme-colored icon-circled icon-xl">
                                <i class="flaticon-profile-female text-theme-colored font-42"></i>
                            </a>
                            <h4 class="icon-box-title text-uppercase letter-space-1 font-20"><a href="#">	كابتن سيدة</a></h4>
                            <p class="">سيدة تنقل سيدة للحفاظ على الخصوصية والراحة والأمان.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="icon-box text-center mb-sm-60">
                            <a href="#" class="icon icon-gray icon-bordered bg-hover-theme-colored icon-circled icon-xl">
                                <i class="flaticon-profile-male text-theme-colored font-42"></i>
                            </a>
                            <h4 class="icon-box-title text-uppercase letter-space-1 font-20"><a href="#">	كابتن رجل</a></h4>
                            <p class="">إيصال العملاء إلى وجهاتهم بسرعة وأمان</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="icon-box text-center mb-sm-60">
                            <a href="#" class="icon icon-gray icon-bordered bg-hover-theme-colored icon-circled icon-xl">
                                <i class="flaticon-flag2 text-theme-colored font-42"></i>
                            </a>
                            <h4 class="icon-box-title text-uppercase letter-space-1 font-20"><a href="#">	رحلة متعددة الأماكن</a></h4>
                            <p class="">إمكانية مرافقة أصدقائك لتحظى برحلة رائعة ومميزة</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="icon-box text-center mb-sm-60">
                            <a href="#" class="icon icon-gray icon-bordered bg-hover-theme-colored icon-circled icon-xl">
                                <i class="flaticon-alarmclock text-theme-colored font-42"></i>
                            </a>
                            <h4 class="icon-box-title text-uppercase letter-space-1 font-20"><a href="#">	رحلة مجدولة</a></h4>
                            <p class="">تفضل يؤمن لك حجز مسبق للوصول إلى وجهتك بالوقت المناسب</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Features -->
{{--    <section id="feauters">--}}
{{--        <div class="container-fluid pt-0 pb-0">--}}
{{--            <div class="row equal-height">--}}
{{--                <div class="col-sm-12 col-md-6 pull-right xs-pull-none bg-silver-light">--}}
{{--                    <div class="row p-60">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <h3 class="text-black-333 font-28 text-uppercase line-bottom mb-40">Our <span class="text-theme-colored">Features</span></h3>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="icon-box left media mb-30">--}}
{{--                                <a href="#" class="media-left pull-left flip mt-5"><i class="flaticon-mobile3 text-theme-colored"></i></a>--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading heading">Fully Responsive</h4>--}}
{{--                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum consectetur sit ullam perspiciatis deserunt</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="icon-box left media mb-30">--}}
{{--                                <a href="#" class="media-left pull-left flip mt-5"><i class="flaticon-puzzle text-theme-colored"></i></a>--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading heading">Powerful Shortcodes</h4>--}}
{{--                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum consectetur sit ullam perspiciatis deserunt</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="icon-box left media mb-sm-30">--}}
{{--                                <a href="#" class="media-left pull-left flip mt-5"><i class="flaticon-tools text-theme-colored"></i></a>--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading heading">Easy To Customize</h4>--}}
{{--                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum consectetur sit ullam perspiciatis deserunt</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="icon-box left media mb-sm-30">--}}
{{--                                <a href="#" class="media-left pull-left flip mt-5"><i class="flaticon-lightbulb text-theme-colored"></i></a>--}}
{{--                                <div class="media-body">--}}
{{--                                    <h4 class="media-heading heading">Unique Design</h4>--}}
{{--                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum consectetur sit ullam perspiciatis deserunt</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-sm-6 col-md-6 p-0 bg-img-cover hidden-sm hidden-xs" data-bg-img="http://placehold.it/950x540">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

{{--    <!-- Section: Our Team -->--}}
{{--    <section id="team">--}}
{{--        <div class="container pb-40 wow fadeInUp" data-wow-duration="1.2s">--}}
{{--            <div class="section-title">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-8 col-md-offset-2 text-center">--}}
{{--                        <h3 class="text-uppercase font-weight-500 font-28 line-bottom-centered mt-0">Creative <span class="text-theme-colored">Team</span></h3>--}}
{{--                        <p class="text-gray-dimgray">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error perspiciatis a ipsa, harum consequatur recusandae odit eligendi. Deleniti, eveniet, ullam.</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="team-members">--}}
{{--                    <div class="col-md-3">--}}
{{--                        <div class="team-members maxwidth400 mb-30">--}}
{{--                            <div class="team-thumb">--}}
{{--                                <img src="http://placehold.it/350x425" alt="" class="img-fullwidth">--}}
{{--                            </div>--}}
{{--                            <div class="team-bottom-part pt-20 pb-10">--}}
{{--                                <h4 class="text-uppercase font-raleway text-theme-colored font-weight-600 line-bottom-center m-0">Oliver Queen <span class="text-gray font-13 ml-5">- Founder</span></h4>--}}
{{--                                <p class="font-13 mt-10 mb-10">Lorem ipsum dolorsit amet consecte turadip isior ipsum dolor sit ametor ipsum dolor sit amet conse</p>--}}
{{--                                <ul class="styled-icons icon-sm">--}}
{{--                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-skype"></i></a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-3">--}}
{{--                        <div class="team-members maxwidth400 mb-30">--}}
{{--                            <div class="team-thumb">--}}
{{--                                <img src="http://placehold.it/350x425" alt="" class="img-fullwidth">--}}
{{--                            </div>--}}
{{--                            <div class="team-bottom-part pt-20 pb-10">--}}
{{--                                <h4 class="text-uppercase font-raleway text-theme-colored font-weight-600 line-bottom-center m-0">John Diggle <span class="text-gray font-13 ml-5">- Developer</span></h4>--}}
{{--                                <p class="font-13 mt-10 mb-10">Lorem ipsum dolorsit amet consecte turadip isior ipsum dolor sit ametor ipsum dolor sit amet conse</p>--}}
{{--                                <ul class="styled-icons icon-sm">--}}
{{--                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-skype"></i></a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-3">--}}
{{--                        <div class="team-members maxwidth400 mb-30">--}}
{{--                            <div class="team-thumb">--}}
{{--                                <img src="http://placehold.it/350x425" alt="" class="img-fullwidth">--}}
{{--                            </div>--}}
{{--                            <div class="team-bottom-part pt-20 pb-10">--}}
{{--                                <h4 class="text-uppercase font-raleway text-theme-colored font-weight-600 line-bottom-center m-0">Laurel Lance <span class="text-gray font-13 ml-5">- Designer</span></h4>--}}
{{--                                <p class="font-13 mt-10 mb-10">Lorem ipsum dolorsit amet consecte turadip isior ipsum dolor sit ametor ipsum dolor sit amet conse</p>--}}
{{--                                <ul class="styled-icons icon-sm">--}}
{{--                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-skype"></i></a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-3">--}}
{{--                        <div class="team-members maxwidth400 mb-30">--}}
{{--                            <div class="team-thumb">--}}
{{--                                <img src="http://placehold.it/350x425" alt="" class="img-fullwidth">--}}
{{--                            </div>--}}
{{--                            <div class="team-bottom-part pt-20 pb-10">--}}
{{--                                <h4 class="text-uppercase font-raleway text-theme-colored font-weight-600 line-bottom-center m-0">Echo Kellum <span class="text-gray font-13 ml-5">- Technologist </span></h4>--}}
{{--                                <p class="font-13 mt-10 mb-10">Lorem ipsum dolorsit amet consecte turadip isior ipsum dolor sit ametor ipsum dolor sit amet conse</p>--}}
{{--                                <ul class="styled-icons icon-sm">--}}
{{--                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--                                    <li><a href="#"><i class="fa fa-skype"></i></a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <!-- Divider: Testimonials -->
    <section class="divider parallax layer-overlay overlay-dark-6" data-bg-img="{{url('website/images/slider/tfadal-taxi.jpg')}}">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="owl-carousel-1col testimonials" data-dots="true">
                        <div class="item">
                            <div class="testimonial-wrapper text-center">
                                <div class="thumb"><img class="img-circle" alt="" src="http://placehold.it/90x90"></div>
                                <div class="content pt-10">
                                    <p class="font-14 text-white mb-30">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Neque est quasi, quas ipsam, expedita placeat facilis odio illo ex accusantium eaque itaque officiis et sit. Vero quo, impedit neque.</p>
                                    <i class="fa fa-quote-right"></i>
                                    <h4 class="author text-theme-colored mb-0">Catherine Grace</h4>
                                    <h6 class="title text-gray-lightgray mt-0 mb-15">Designer</h6>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonial-wrapper text-center">
                                <div class="thumb"><img class="img-circle" alt="" src="http://placehold.it/90x90"></div>
                                <div class="content pt-10">
                                    <p class="font-14 text-white mb-30">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Neque est quasi, quas ipsam, expedita placeat facilis odio illo ex accusantium eaque itaque officiis et sit. Vero quo, impedit neque.</p>
                                    <i class="fa fa-quote-right"></i>
                                    <h4 class="author text-theme-colored mb-0">Catherine Grace</h4>
                                    <h6 class="title text-gray-lightgray mt-0 mb-15">Designer</h6>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonial-wrapper text-center">
                                <div class="thumb"><img class="img-circle" alt="" src="http://placehold.it/90x90"></div>
                                <div class="content pt-10">
                                    <p class="font-14 text-white mb-30">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Neque est quasi, quas ipsam, expedita placeat facilis odio illo ex accusantium eaque itaque officiis et sit. Vero quo, impedit neque.</p>
                                    <i class="fa fa-quote-right"></i>
                                    <h4 class="author text-theme-colored mb-0">Catherine Grace</h4>
                                    <h6 class="title text-gray-lightgray mt-0 mb-15">Designer</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: blog -->
    <section id="blog">
        <div class="container pb-40 wow fadeInUp" data-wow-duration="1.2s">
            <div class="section-title">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <h3 class="text-uppercase font-weight-500 font-28 line-bottom-centered mt-0"><span class="text-theme-colored">حمّل التطبيق وابدأ المغامرة </span></h3>
                        <p class="text-gray-dimgray">يمكنك تحميل التطبيقات من هنا</p>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="row">
                    <div class="col-md-6">
                        <article class="post bg-lighter maxwidth400 mb-30">
                            <div class="entry-header">
                                <a href="https://play.google.com/store/apps/details?id=com.bis.taxi">
                                <div class="post-thumb thumb">
                                    <img src="{{url('website/images/Android.png')}}" alt="android driver" class="img-responsive img-fullwidth">
                                </div>
                                </a>
                            </div>
                            <div class="entry-content bg-white border-1px p-20">
                                <div class="entry-meta">
                                    <ul class="list-inline font-11 letter-space-1 mb-10">
                                        <li>مجموعة تكرم |</li>
                                        <li>تطبيق العميل (اندرويد)</li>
                                    </ul>
                                </div>
                                <h4 class="entry-title font-weight-500 text-uppercase line-bottom letter-space-1"><a href="https://play.google.com/store/apps/details?id=com.bis.taxi">تطبيق العميل (اندرويد) </a></h4>
                                <p class="mt-15">Lorem ipsum dolor sit amet, consect eturadi piscing elit. Nulla auctor, erat nec effician turpharetra, metus ligula finibus</p>
                                <div class="entry-meta mt-20 mb-10">

                                </div>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6">
                        <article class="post bg-lighter maxwidth400 mb-30">
                            <div class="entry-header">
                                <a href="#">
                                <div class="post-thumb thumb">
                                   <a href="#"><img src="{{url('website/images/IOS.png')}}" alt="" class="img-responsive img-fullwidth"></a>
                                </div>
                                </a>
                            </div>
                            <div class="entry-content bg-white border-1px p-20">
                                <div class="entry-meta">
                                    <ul class="list-inline font-11 letter-space-1 mb-10">
                                        <li>مجموعة تكرم |</li>
                                        <li>تطبيق العميل (ايفون)</li>
                                    </ul>
                                </div>
                                <h4 class="entry-title font-weight-500 text-uppercase line-bottom letter-space-1"><a href="#">تطبيق العميل (ايفون) </a></h4>
                                <p class="mt-15">Lorem ipsum dolor sit amet, consect eturadi piscing elit. Nulla auctor, erat nec effician turpharetra, metus ligula finibus</p>
                                <div class="entry-meta mt-20 mb-10">
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <article class="post bg-lighter maxwidth400 mb-30">
                            <div class="entry-header">
                                <a href="https://play.google.com/store/apps/details?id=com.bis.friends_drivers">
                                    <div class="post-thumb thumb">
                                        <img src="{{url('website/images/Android.png')}}" alt="android driver" class="img-responsive img-fullwidth">
                                    </div>
                                </a>
                            </div>
                            <div class="entry-content bg-white border-1px p-20">
                                <div class="entry-meta">
                                    <ul class="list-inline font-11 letter-space-1 mb-10">
                                        <li>مجموعة تكرم |</li>
                                        <li>تطبيق الكابتن (اندرويد)</li>
                                    </ul>
                                </div>
                                <h4 class="entry-title font-weight-500 text-uppercase line-bottom letter-space-1"><a href="https://play.google.com/store/apps/details?id=com.bis.friends_drivers">تطبيق الكابتن (اندرويد) </a></h4>
                                <p class="mt-15">Lorem ipsum dolor sit amet, consect eturadi piscing elit. Nulla auctor, erat nec effician turpharetra, metus ligula finibus</p>
                                <div class="entry-meta mt-20 mb-10">

                                </div>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6">
                        <article class="post bg-lighter maxwidth400 mb-30">
                            <div class="entry-header">
                                <a href="#">
                                <div class="post-thumb thumb">
                                    <a href="#"><img src="{{url('website/images/IOS.png')}}" alt="" class="img-responsive img-fullwidth"></a>
                                </div>
                                </a>
                            </div>
                            <div class="entry-content bg-white border-1px p-20">
                                <div class="entry-meta">
                                    <ul class="list-inline font-11 letter-space-1 mb-10">
                                        <li>مجموعة تكرم |</li>
                                        <li>تطبيق الكابتن (ايفون)</li>
                                    </ul>
                                </div>
                                <h4 class="entry-title font-weight-500 text-uppercase line-bottom letter-space-1"><a href="#">تطبيق الكابتن (ايفون) </a></h4>
                                <p class="mt-15">Lorem ipsum dolor sit amet, consect eturadi piscing elit. Nulla auctor, erat nec effician turpharetra, metus ligula finibus</p>
                                <div class="entry-meta mt-20 mb-10">
                                </div>
                            </div>
                        </article>
                    </div>
                </div>


            </div>
        </div>
    </section>



    <!-- Divider: Clients -->
    <section class="clients bg-theme-colored">
        <div class="container pt-0 pb-0">
            <div class="row">
                <div class="col-md-12">
                    <!-- Section: Clients -->
                    <div class="owl-carousel-6col clients-logo transparent text-center">
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                        <div class="item"> <a href="#"><img src="http://placehold.it/200x120" alt=""></a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
