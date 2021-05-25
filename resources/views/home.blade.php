<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_NAME')}}</title>
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}" />
    <link href="{{asset('css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/owl.theme.default.min.css')}}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{asset('css/aos.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('css/style.css')}}" />
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
                                <img class="logo-img" src="{{asset('images/logo.svg')}}" alt="logo"></a>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!--End-->
        <div class="under-construction">
           <div class="image">
               <img src="{{asset('images/under-construction.jpg')}}" alt="Under Construction" class="img-fluid" />
           </div>
        </div>
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