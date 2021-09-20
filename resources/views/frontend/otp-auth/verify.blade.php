@extends('frontend.layouts.app')
@section('title', __('Verify OTP'))

@section('content')
<div class="login-page">
    <div class="container">
        <div class="login-signup-wrapper">
            <div class="row m-0">
                <div class="col-lg-5 left p-0 ">
                    <div class="content">
                        <div class="logo">
                            <a href="{{ url('/') }}"><img src="{{asset(env('PUBLIC_URL').'images/logo.svg')}}" alt="" class="img-fluid" /></a>
                        </div>
                        <p>Verify OTP</p>
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
                            <div class="tab-pane fade show active" id="v-pills-signup" role="tabpanel"
                                aria-labelledby="v-pills-signup-tab">
                                <div class="form-wrapper">
                                    <h2>Verify OTP</h2>
                                    @include('admin.messages')

                                    <form id="login-form" method="POST" action="{{ url('/otp-auth/verify-two-factor-authentication') }}">
                                        {{ csrf_field() }}

                                        <div class="form-group" style="display: none;">
                                            <input type="email" class="form-control" name="email" value="{{ $email }}" placeholder="{{ __('Email Address') }}" required>
                                        </div>
                                        <div class="form-group" style="display: none;">
                                            <input type="password" class="form-control" name="password" value="{{ $password }}" placeholder="{{ __('Password') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" name="one_time_password" placeholder="{{ __('One Time Password') }}" required>
                                        </div>

                                        <div class="form__verify form-group button-color">
                                            <a href="{{ route('login') }}"><a href="{{route('login')}}" class="btn btn-danger btn-lg">{{__('Cancel')}}</a></a>
                                            <button type="submit" class="btn btn-success btn-lg">{{__('Authenticate')}}</button>
                                        </div>
                                        <div class="forget-password"><a href="{{ url('/otp-auth/reset-two-factor-authentication').'?id='.$id }}">{{__('Forgot 2FA')}}</a></div>
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
        $(function () {
            $('#login-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: false,

                rules: {
                    email: {
                        emailCheck: true,
                        required: true,
                    },
                    password: {
                        passwordCheck:true,
                        required: true,
                    },
                    one_time_password: {
                        required: true,
                        digits: true,
                        minlength: 6,
                        maxlength: 6,
                    },
                },

                messages: {
                    email: {
                        emailCheck: '{{__('Please enter a valid email address')}}',
                        required: '{{__('This field is required')}}'
                    },
                    password: {
                        passwordCheck: '{{__('Must contain at least one number and one uppercase and lowercase letter and at least 8 or more characters')}}',
                        required: '{{__('This field is required')}}'
                    },
                    one_time_password: {
                        required: '{{__('This field is required')}}',
                        digits: '{{__('The one time password must be a number.')}}',
                        minlength: '{{__('The one time password must be 6 digits.')}}',
                        maxlength: '{{__('The one time password must be 6 digits.')}}',
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
            $.validator.addMethod("passwordCheck", function (value) {
                return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(value)
            });
            $.validator.addMethod("emailCheck", function (value) {
                return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)
            });
        });
    </script>
@endsection
