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
                                <a href="{{ url('/') }}"><img src="{{asset(env('PUBLIC_URL').'images/logo.svg')}}" alt="" class="img-fluid" /></a>
                            </div>
                            <p>Account Settings</p>
                        </div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active mr-lg-0 mr-1" href="{{ url('/profile') }}">Profile</a>
                            <a class="nav-link mr-lg-0 mr-1" href="{{ url('/documents') }}">KYC</a>
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
                                                 <!-- onClick="window.open('update_email');" -->
                                                <a   class="btn-theme float-right"  href="{{ url('update_email') }}" title="update Email" style="color: #fff;margin-top: 10px;  padding: 12px 22px;"> Update Email</a> 
                                            </div>
                                            <div class="form-group">
                                                <input type="password" id="password" class="form-control" name="password" placeholder="Password" minlength="8" maxlength="30" value="{{$user->original_password}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{$user->original_password}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control"  placeholder="Mobile Number+61 xxxxxxxxx 04xxxxxxxx  +61xxxxxxxxx*" name="mobile_number" value="{{$user->mobile_number}}" minlength="8" maxlength="20" required="required">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Date of Birth (DD-MM-YYY)*" name="dob" value="{{date('m-d-Y', strtotime($user->dob))}}" id="my_date_picker"  required="required">
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
                                            <!-- <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Emergency ID Verification Code" name="emergency_id_verification_code" value="{{$user->emergency_id_verification_code}}" required="required">
                                            </div> -->

                                            @if(!empty($user->referral_code))
                                                <p><strong>Referral Code.</strong></p>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="{{$user->referral_code}}" readonly="">
                                                </div>
                                            @else
                                                @if($user->referral_code_end_date >= date('Y-m-d') || $user->referral_code_end_date == null)
                                                    <p><strong>Referral Code.</strong></p>
                                                    <div class="form-group">
                                                        <input type="text" name="referral_code" class="form-control" placeholder="Referral Code">
                                                    </div>

                                                    <p>You have till {{ $user->referral_code_end_date }} date to provide Referral Code.</p>
                                                @endif
                                            @endif

                                            <p><strong>Wallet Address</strong></p>

                                            <div class="form-group" id="btc_wallet_address">
                                                <input type="text" name="btc_wallet_address"  class="form-control" placeholder="BTC Wallet Address" value="{{$user->btc_wallet_address}}" required="required">
                                            </div>

                                            <p><strong>Memo Address</strong></p>

                                            <div class="form-group" id="btc_wallet_address">
                                                <input type="text" name="memo_address"  class="form-control" placeholder="Memo Address" value="{{$user->memo_address}}"  >
                                            </div>

                                            <p><strong>Email OTP Verification</strong></p>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="email_otp_status" id="enable" value="1" {{ ($user->email_otp_status == 1) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable">
                                                Enable
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                               <input class="form-check-input" type="radio" name="email_otp_status" id="disable" value="2" {{ ($user->email_otp_status == 2) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="disable">
                                                Disable
                                              </label>
                                            </div>
                                            
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
                    name: {
                        spaceCheckWithAlphabet: "Please enter valid name",
                    },
                    family_name: {
                        spaceCheckWithAlphabet: "Please enter valid family name",
                    },
                    street: {
                        spaceCheck: "Please enter valid address",
                    },
                    city: {
                        spaceCheckWithAlphabet: "Please enter valid suburb name",
                        
                    },
                    state: {
                        spaceCheckWithAlphabet: "Please enter valid state",
                        
                    },
                    password: {
                        passwordCheck: true
                    },
                    password_confirmation: {
                        equalTo: "#password"
                    },
                    mobile_number: {
                        minlength: 8, maxlength: 30, mobileCheck: true
                    },
                    btc_wallet_address: {
                        walletAddressCheck: true
                    },
                },

                messages: {
                    name: {
                        spaceCheckWithAlphabet: "Please enter valid name",
                    },
                    family_name: {
                        spaceCheckWithAlphabet: "Please enter valid family name",
                    },
                    street: {
                        spaceCheck: "Please enter valid address",
                        
                    },
                    city: {
                        spaceCheckWithAlphabet: "Please enter valid suburb name",
                    },
                    state: {
                        spaceCheckWithAlphabet: "Please enter valid state",
                    },
                    password: {
                        passwordCheck: "Minimum 8 or more characters, at least one uppercase letter, one lowercase letter, one number and one special character.",
                    },
                    mobile_number: {
                         mobileCheck: "Please enter a valid mobile number e.g +61 123456789."
                    },
                    btc_wallet_address: {
                        walletAddressCheck: "Please enter valid wallet address and no special character are used."
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
                        // error.insertAfter(element.parent());
                        error.insertAfter(element);
                },
                invalidHandler: function (form,validator) {
                },
            });
            $.validator.addMethod("spaceCheckWithAlphabet", function (value) {
                return /^[a-zA-Z][a-zA-Z]+/.test(value)
            });
            $.validator.addMethod("spaceCheck", function (value) {
                return /^[^\s].+[^\s]/.test(value)
            });
            $.validator.addMethod("passwordCheck", function (value) {
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_.#])[A-Za-z\d@$!%*?&_.#]{8,}/.test(value)
            });
            $.validator.addMethod("mobileCheck", function (value) {
            // return /^(([+][(]?[0-9]{1,3}[)]?))\s*[)]?[-\s\.]?[(]?[0-9]{1,3}[)]?([-\s\.]?[0-9]{3})([-\s\.]?[0-9]{3,4})$/.test(value)
             return /^(([(]?[+0-9]{1,3}[)]?))\s*[)]?[-\s\.]?[(]?[0-9]{1,3}[)]?([-\s+\.]?[0-9]{3})([-\s+\.]?[0-9\+]{3,4})$/.test(value) 
             // return /^(?=.*[0-9])(?=.*\d)(?=.*[0-9])(?=.*[+])[0-9\d+]{12,}/.test(value)
            });
            $.validator.addMethod("walletAddressCheck", function (value) {
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\d)[A-Za-z0-9\d]/.test(value)
            });
        });
    
    $(function() {
        var maxBirthdayDate = new Date();
        maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18 );
        $( "#my_date_picker" ).datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRanger : "-100",
                //minDate: new Date(1970,06,22),
                yearRange: "-800:+0",
                minDate: new Date(1220,1 - 1),
                // maxDate: '-18Y',
                 maxDate: maxBirthdayDate,
            });
    });

    </script>

@endsection
