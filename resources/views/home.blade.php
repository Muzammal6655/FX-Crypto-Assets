<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_NAME')}}</title>
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/bootstrap.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/font-awesome.min.css')}}" />
    <link href="{{asset(env('PUBLIC_URL').'css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('PUBLIC_URL').'css/owl.theme.default.min.css')}}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/aos.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/style.css')}}" />
</head>

<body>
    <div class="wrapper">
        <!--Header Section-->
        <header class="site-header">
            <div class="container-fluid">
                <div class="main-menu">
                    <div class="masthead-des">
                        <!-- navbar-->
                        <nav class="navbar navbar-expand-lg navbar-light main-nav fill">
                            <a class="navbar-brand js-scroll-trigger" href="{{ url('/') }}">
                                <img class="logo-img" src="{{asset(env('PUBLIC_URL').'images/logo.svg')}}" alt="logo"></a>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!--End-->
        <div class="under-construction">
           <div class="image">
               <img src="{{asset(env('PUBLIC_URL').'images/under-construction.jpg')}}" alt="Under Construction" class="img-fluid" />
           </div>
        </div>
        <footer class="footer">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-content">
                                <h4>About Interesting FX</h4>
                                <p>Neque porro quisquam est qui dolore ipsum quia dolor sit amet, consectet urdipisci
                                    velit nec ultricies est mauris quis lorem scelisque justo lacu.
                                    <a class="read-more" href="#">Read more>></a>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
        
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-content contact-detail">
                                <h4>Contact</h4>
                                <ul class="list-unstyled info-list">
                                    <li>
                                        <a href="mailto:glenn@xxxxxxxxxx"><span class="icon fa fa-envelope"></span>glenn@xxxxxxxxxx</a>
                                    </li>
                                    <li>
                                        <a href="tel:+61xxxxxxxx80"><span class="icon fa fa-phone"></span>+61xxxxxxxx80</a>
                                    </li>
                                </ul>
                                <h4 class="social-heading">Social</h4>
                                <ul class="list-unstyled social-list">
                                    <li>
                                        <a href="#">
                                            <span class="icon-circle fa fa-facebook"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="icon-circle fa fa-twitter"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="icon-circle fa fa-google-plus"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom copyright">
                <div class="container">
                    <div class="copyright-content">
                        <p>Copyright © {{env('APP_NAME')}} {{ date('Y') }}. All Rights Reserved</p>
                        <div class="chat">
                            <img src="{{asset('images/chat.png')}}" alt="chat icon" class="img-fluid" />
                        </div>
                        <div class="asoft">
                            <a href="#">
                                <img src="{{asset('images/asoft.png')}}" alt="ArhamSoft Logo" class="img-fluid" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script type="application/javascript" src="{{asset('js/jquery.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/aos.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/style.js')}}"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>