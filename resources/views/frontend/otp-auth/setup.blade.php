@extends('frontend.layouts.app')
@section('title', 'Setup Google 2FA')

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
                        <p>Configure Google Authenticator</p>
                    </div>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <a class="nav-link" href="{{ url('/profile') }}">Profile</a>
                        <a class="nav-link" href="{{ url('/documents') }}">KYC</a>
                        <a class="nav-link active" href="{{ url('/otp-auth/info') }}">2FA</a>
                    </div>
                </div>
                <div class="col-lg-7 right">
                    <div class="content-wrapper">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-signup" role="tabpanel"
                                aria-labelledby="v-pills-signup-tab">
                                <div class="form-wrapper">
                                    <h2>Two-factor Authentication (2FA)</h2>
                                    @include('admin.messages')
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p>{{__('Configure your 2FA by scanning the QR CODE below.')}}</p>
                                            <center>
                                                <p> {!! $otp_auth_qr_image !!}</p>
                                            </center>
                                            <p>{{__('You must configure your Google Authenticator app before continuing. You will be unable to login otherwise.')}}</p>

                                            <form id="otp-setup-form" action="{{url('/otp-auth/enable-two-factor-authentication')}}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="text" class="form-control"  placeholder="{{ __('One Time Password') }}" name="one_time_password" required>
                                                </div>

                                                <div class="text-right">
                                                    <a href="{{url('/otp-auth/info')}}" class="btn btn-default"><span>{{__('Cancel')}}</span></a>
                                                    <button type="submit" class="btn btn-primary">
                                                        <span>{{__('Enable 2FA')}}</span>
                                                    </button>
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
    </div>
</div>
@endsection

@section('js')
<script>
    $(function () {
        $('#otp-setup-form').validate({
            errorElement: 'div',
            errorClass: 'help-block text-danger',
            focusInvalid: true,

            rules: {
                one_time_password: {
                    required:true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6,                  
                },
            },

            messages: {
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
        });
    });
 </script>

@endsection

