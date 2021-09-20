@extends('frontend.layouts.app')
@section('title', '2FA')

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
                            <p>Two-factor Authentication (2FA)</p>
                        </div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link mr-lg-0 mr-1" href="{{ url('/profile') }}">Profile</a>
                            <a class="nav-link mr-lg-0 mr-1" href="{{ url('/documents') }}">KYC</a>
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
                                        <p><strong><a href="https://en.wikipedia.org/wiki/Multi-factor_authentication"
                                          target="_blank">Two-factor Authentication (2FA)</a></strong> adds additional account security if your password is compromised or stolen. With 2FA, access to your account requires a password and a second form of verification.</p>
                                        <p><strong>{{ env('APP_NAME') }}</strong> supports 2FA by using one-time passwords generated with the 
                                        <strong><a href="https://en.wikipedia.org/wiki/Time-based_One-time_Password_algorithm" target="_blank">TOTP algorithm</a></strong></p>
                                        <p>You can use any mobile application employing TOTP.</p>
                                        <p>We recommend the following apps</p>
                                        <p>Android, iOS, and Blackberry—<strong><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&amp;hl=en" target="_blank">Google Authenticator</a></strong></p>

                                        @if($user->otp_auth_status)
                                            <a href="{{url('otp-auth/disable-two-factor-authentication')}}">
                                                <button type="button" class="btn btn-primary btn-fullrounded">Disable</button>
                                            </a>
                                        @else
                                            <a href="{{url('otp-auth/setup-two-factor-authentication')}}">
                                                <button type="button" class="btn btn-primary btn-fullrounded">Configure</button>
                                            </a>
                                        @endif
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
