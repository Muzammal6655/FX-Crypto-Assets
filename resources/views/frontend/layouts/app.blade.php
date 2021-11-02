<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_NAME')}} | @yield('title')</title>
    <link rel="icon" href="{{asset(env('PUBLIC_URL').'images/favicon.png')}}" type="image/x-icon">
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/bootstrap.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/select2/css/select2.min.css') }}">
    <link href="{{asset(env('PUBLIC_URL').'css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset(env('PUBLIC_URL').'css/owl.theme.default.min.css')}}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/aos.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset(env('PUBLIC_URL').'css/style.css')}}" />
    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'rel='stylesheet'>

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
    <script type="application/javascript" src="{{asset(env('PUBLIC_URL').'js/jquery.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="application/javascript" src="{{asset(env('PUBLIC_URL').'js/bootstrap.min.js')}}"></script>
    <script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset(env('PUBLIC_URL').'admin-assets/js/jquery.validate.js') }}"></script>
    <script src="{{asset(env('PUBLIC_URL').'js/owl.carousel.min.js')}}"></script>
    <script type="application/javascript" src="{{asset(env('PUBLIC_URL').'js/aos.js')}}"></script>
    <script type="application/javascript" src="{{asset(env('PUBLIC_URL').'js/style.js')}}"></script>
  

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        AOS.init();
        if(!$('.alert').hasClass('persist-alert'))
        {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 10000);
        }
    </script>
    @yield('js')
</body>

</html>