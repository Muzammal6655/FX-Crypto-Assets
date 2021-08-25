@extends('frontend.layouts.app')
@section('title', 'Profile')

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
                            <p>Account Settings</p>
                        </div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active" href="{{ url('/profile') }}">Profile</a>
                            <a class="nav-link" href="{{ url('/documents') }}">KYC</a>
                            <a class="nav-link" href="{{ url('/otp-auth/info') }}">2FA</a>
                        </div>
                    </div>
                    <div class="col-lg-7 right">
                        <div class="content-wrapper">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-signup" role="tabpanel"
                                    aria-labelledby="v-pills-signup-tab">
                                    <div class="form-wrapper">
                                        <h2>Account Settings</h2>
                                        @include('frontend.messages')
                                        <form class="text-left" id="profile-form" method="POST" action="{{ url('/profile') }}">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="name" maxlength="100" placeholder="First Name" value="{{$user->name}}" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="family_name" maxlength="30" placeholder="Family Name" value="{{$user->family_name}}" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" maxlength="100" placeholder="Email" value="{{$user->email}}" readonly="">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" id="password" class="form-control" name="password" placeholder="Password" minlength="8" maxlength="30" value="{{$user->original_password}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{$user->original_password}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Mobile Number" name="mobile_number" value="{{$user->mobile_number}}" minlength="8" maxlength="20" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="date" class="form-control" placeholder="Date of Birth" name="dob" value="{{$user->dob}}" max="{{ date('Y-m-d', strtotime('-18 year')) }}" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Address" name="street" value="{{$user->street}}" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Suburb" name="city" value="{{$user->city}}" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="State" name="state" value="{{$user->state}}" required="required">
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" name="country_id" id="country_id" required="required">
                                                    <option value="">Select Country</option>
                                                    @foreach ($countries as $country)
                                                        @if($country->id == $user->country_id)
                                                            <option value="{{$country->id}}" selected="">{{$country->name}}</option>
                                                        @else
                                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Emergency ID Verification Code" name="emergency_id_verification_code" value="{{$user->emergency_id_verification_code}}" required="required">
                                            </div>

                                            @if(!empty($user->referral_code))
                                                <p><strong>Referral Code.</strong></p>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="{{$user->referral_code}}" readonly="">
                                                </div>
                                            @else
                                                @if($user->referral_code_end_date >= date('Y-m-d'))
                                                    <p><strong>Referral Code.</strong></p>
                                                    <div class="form-group">
                                                        <input type="text" name="referral_code" class="form-control" placeholder="Referral Code">
                                                    </div>

                                                    <p>You have till {{ $user->referral_code_end_date }} date to provide Referral Code.</p>
                                                @endif
                                            @endif

                                            <p><strong>Wallet Address</strong></p>

                                            <div class="form-group" id="btc_wallet_address">
                                                <input type="text" name="btc_wallet_address" class="form-control" placeholder="BTC Wallet Address" value="{{$user->btc_wallet_address}}">
                                            </div>
                                            <h5>OTP Verification</h5>
                                            <div class="form-group">
                                                <label for="email_code">
                                                    Email Code
                                                    <button class="btn btn-outline-warning" type="button" id="generate_otp">Generate OTP <i class="fa fa-spinner fa-spin" id="generate_otp_loading" style="display: none;"></i></button>
                                                </label>
                                                <input type="number" class="form-control" id="email_code" name="email_code" value="{{ old('email_code') }}" minlength="6" maxlength="6" required="">
                                            </div>
                                            @if($user->otp_auth_status == 1)
                                            <div class="form-group">
                                                <label for="two_fa_code">2FA Code</label>
                                                <input type="number" class="form-control" id="two_fa_code" name="two_fa_code" value="{{ old('two_fa_code') }}" required="">
                                            </div>
                                            @endif

                                            <div class="bottom">
                                                <div class="btn-wrap">
                                                    <button type="submit" class="btn-theme text-capitalize">Save
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
        $(function () {
            $('#country_id').select2(
            {
                placeholder: 'Select a Country',
                allowClear: true
            });

            $('#profile-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: true,

                rules: {
                    password: {
                        passwordCheck: true
                    },
                    password_confirmation: {
                        equalTo: "#password"
                    }
                },

                messages: {
                    password: {
                        passwordCheck: "Minimum 8 or more characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                    }
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
                        var controls = element.closest('.form-group');
                        if (controls.find(':checkbox,:radio').length >= 1)
                        {
                            controls.append(error);
                        }
                        else
                        {
                            error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        }
                    } else if (element.is('.select2')) {
                        error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                    } else if (element.is('.chosen-select')) {
                        error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                    } else
                        error.insertAfter(element.parent());
                },
                invalidHandler: function (form,validator) {
                },
            });
            $.validator.addMethod("passwordCheck", function (value) {
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/.test(value)
            });
            $("#generate_otp").click(function(){
                $('#generate_otp_loading').show();
                $('#generate_otp').prop('disabled',true);

                $.ajax({
                    url: "{{ url('/otp-auth/send-email-code?type=profile') }}",
                    type: 'GET',
                    success: function(res) {
                        $('#generate_otp_loading').hide();
                        $('#generate_otp').prop('disabled',false);
                        alert(res);
                    }
                });
            });
        });

    </script>

@endsection
