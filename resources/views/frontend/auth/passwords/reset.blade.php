@extends('frontend.layouts.app')
@section('title', 'Reset Password')

@section('content')
    <div class="login-page">
        <div class="container">
            <div class="login-signup-wrapper">
                <div class="row m-0">
                    <div class="col-lg-5 left p-0 ">
                        <div class="content">
                            <div class="logo">
                                <a href="{{ url('/') }}"><img src="{{asset('images/logo.svg')}}" alt="" class="img-fluid" /></a>
                            </div>
                            <p>Join the world's largest crypto exchange</p>
                        </div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active mr-lg-0 mr-1" href="{{ url('/login') }}">Log In</a>
                            <a class="nav-link" href="{{ url('/register') }}">Sign Up</a>
                        </div>
                    </div>
                    <div class="col-lg-7 right">
                        <div class="content-wrapper">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-password" role="tabpanel"
                                    aria-labelledby="v-pills-password-tab">
                                    <div class="form-wrapper">
                                        <h2>Reset Password</h2>
                                        @include('frontend.messages')
                                        <form class="text-right" id="reset-password" method="post" action="{{ route('auth.reset-password') }}">
                                            @csrf
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" value="{{$email}}" readonly="">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required="required">
                                            </div>
                                            <div class="btn-wrap">
                                                <button type="submit" class="btn-theme"> Reset Password
                                                    <span class="btn-theme__inner">
                                                        <span class="btn-theme__blobs">
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                        </span>
                                                    </span>
                                                </button>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                    class="btn-svg">
                                                    <defs>
                                                        <filter id="goo">
                                                            <feGaussianBlur in="SourceGraphic" result="blur"
                                                                stdDeviation="10"></feGaussianBlur>
                                                            <feColorMatrix in="blur" mode="matrix"
                                                                values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 21 -7"
                                                                result="goo"></feColorMatrix>
                                                            <feBlend in2="goo" in="SourceGraphic" result="mix">
                                                            </feBlend>
                                                        </filter>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(function(){
        $('#reset-password').validate({
            errorElement: 'div',
            errorClass: 'help-block text-danger',
            focusInvalid: false,

            rules: {
                password: {
                    passwordCheck:true
                },
                password_confirmation: {
                    equalTo: "#password"
                },
            },

            messages: {
                password: {
                    passwordCheck: "Minimum 8 or more characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                },
            },

            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error');
                $(e).remove();
            },
            errorPlacement: function (error, element) {
                if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="col-"]');
                    if (controls.find(':checkbox,:radio').length > 1)
                        controls.append(error);
                    else
                        error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                } else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                } else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                } else
                    error.insertAfter(element.parent());
            },
            invalidHandler: function (form) {
            }
        });
        $.validator.addMethod("passwordCheck", function(value) {
           return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_.#])[A-Za-z\d@$!%*?&_.#]{8,}/.test(value)
        });
    });

</script>
    @endsection
