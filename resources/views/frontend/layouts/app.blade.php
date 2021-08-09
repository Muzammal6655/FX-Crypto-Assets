<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_NAME')}} | @yield('title')</title>
    <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('admin-assets/vendor/select2/css/select2.min.css') }}">
    <link href="{{asset('css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/owl.theme.default.min.css')}}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{asset('css/aos.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('css/style.css')}}" />
</head>

<body>
    <div class="wrapper">
        <!--Header Section-->
        @include('frontend.sections.header')
        <!--End-->
        
        @yield('content')

        <!--footer-->
        @include('frontend.sections.footer')
        <!--End footer-->
    </div>
    <script type="application/javascript" src="{{asset('js/jquery.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('admin-assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/jquery.validate.js') }}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/aos.js')}}"></script>
    <script type="application/javascript" src="{{asset('js/style.js')}}"></script>
    <script>
        AOS.init();
        if(!$('.alert').hasClass('persist-alert'))
        {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        }
    </script>
    @yield('js')
</body>

</html>