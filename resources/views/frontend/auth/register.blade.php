@extends('frontend.layouts.app')
@section('title', 'Register')

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
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link" href="{{ url('/login') }}">Log In</a>
                        <a class="nav-link active" href="{{ url('/register') }}">Sign Up</a>
                    </div>
                </div>
                <div class="col-lg-7 right">
                    <div class="content-wrapper">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-signup" role="tabpanel" aria-labelledby="v-pills-signup-tab">
                                <div class="form-wrapper">
                                    <h2>Join Interesting FX</h2>
                                    @include('frontend.messages')
                                    <form class="text-left" id="signup-form" method="POST" action="{{ route('register') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name" maxlength="100" placeholder="First Name" value="{{old('name')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="family_name" maxlength="30" placeholder="Family Name" value="{{old('family_name')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" maxlength="100" placeholder="Email" value="{{old('email')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="Password" value="{{old('password')}}" minlength="8" maxlength="30" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{old('password')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Mobile Number" name="mobile_number" value="{{old('mobile_number')}}" minlength="8" maxlength="20" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="date" class="form-control" placeholder="Date of Birth" name="dob" value="{{old('dob')}}" max="{{ date('Y-m-d', strtotime('-18 year')) }}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Address" name="street" value="{{old('street')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Suburb" name="city" value="{{old('city')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="State" name="state" value="{{old('state')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="country_id" id="country_id" required="required">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                @if($country->id == old('country_id'))
                                                <option value="{{$country->id}}" selected="">{{$country->name}}</option>
                                                @else
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Emergency ID Verification Code" name="emergency_id_verification_code" value="{{old('emergency_id_verification_code')}}" required="required">
                                        </div>

                                        <p><strong>Were you referred to Interesting FX?</strong></p>

                                        <div class="form-group">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="ReferredOptions" value="yes" id="ReferredOptions1" required="" {{ (old('ReferredOptions') == 'yes' || !empty($referral_code)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ReferredOptions1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="ReferredOptions" value="no" id="ReferredOptions2" {{ old('ReferredOptions') == 'no' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ReferredOptions2">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group referral-code" style="display: {{ (old('ReferredOptions') == 'yes' || !empty($referral_code)) ? '' : 'none' }}">
                                            <input type="text" name="referral_code" class="form-control" placeholder="Referral Code" value="{{old('referral_code') ?? $referral_code}}">
                                        </div>

                                        <div class="form-check referral-code" style="display: {{ (old('ReferredOptions') == 'yes' || !empty($referral_code)) ? '' : 'none' }}">
                                            <input type="checkbox" class="form-check-input" name="provide_later" id="provide_later" {{ old('provide_later') == 'on' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="provide_later">Provide Later</label>
                                        </div>

                                        <p id="provide_later_text" style="display: {{ old('provide_later') == 'on' ? '' : 'none' }}">You have till the last day of next month to provide Referral Code.</p>

                                        <p><strong>Do you have an Existing BTC wallet for withdrawals?</strong></p>

                                        <div class="form-group">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="BTCOptions" value="yes" id="BTCOptions1" required="" {{ old('BTCOptions') == 'yes' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="BTCOptions1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="BTCOptions" value="no" id="BTCOptions2" {{ old('BTCOptions') == 'no' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="BTCOptions2">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group" id="btc_wallet_address" style="display: {{ old('BTCOptions') == 'yes' ? '' : 'none' }}">
                                            <input type="text" name="btc_wallet_address" class="form-control" placeholder="BTC Wallet Address" value="{{old('btc_wallet_address')}}">
                                        </div>

                                        <p id="binance" style="display: {{ old('BTCOptions') == 'no' ? '' : 'none' }};"><a href="https://www.binance.com/en/register?ref=CBPE2Z8R" target="_blank">Binance</a> - Interesting FX is paid a referral fee for referring our customers to Binance. Interesting FX does not require you to use Binance we offer this link purely as a service.</p>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input data-is-link-open=0 data-term-and-condition-link="{{ url('/pages/terms/') }}" id="term_and_condition" type="checkbox" name="agree" class="form-check-input" required="" {{ old('agree') == 'on' ? 'checked' : '' }}>
                                                <label class="form-check-label">I have read and agree to the <a href="{{ url('/pages/terms/') }}" target="_blank">T&C</a></label>
                                            </div>
                                        </div>

                                        <div class="bottom">
                                            <p>Already have an account <a href="{{ url('/login') }}">click here</a> to login
                                            </p>
                                            <div class="btn-wrap">
                                                <button type="submit" class="btn-theme text-capitalize">Create
                                                    Account
                                                    <span class="btn-theme__inner">
                                                        <span class="btn-theme__blobs">
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                            <span class="btn-theme__blob"></span>
                                                        </span>
                                                    </span>
                                                </button>
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="btn-svg">
                                                    <defs>
                                                        <filter id="goo">
                                                            <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="10"></feGaussianBlur>
                                                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 21 -7" result="goo"></feColorMatrix>
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
    $(function() {
        $('input[name=BTCOptions]').change(function() {
            if (this.value == 'yes') {
                $('#btc_wallet_address').show();
                $('#binance').hide();
            } else if (this.value == 'no') {
                $('#btc_wallet_address').hide();
                //$('input[name=btc_wallet_address]').val('');
                $('#binance').show();
            }
        });

        $('input[name=ReferredOptions]').change(function() {
            if (this.value == 'yes') {
                $('.referral-code').show();
            } else if (this.value == 'no') {
                $('.referral-code').hide();
                //$('input[name=referral_code]').val('');
            }
        });

        $('#provide_later').change(function() {
            if ($(this).is(":checked")) {
                $('#provide_later_text').show();
            } else {
                $('#provide_later_text').hide();
            }
        });

        $('#country_id').select2({
            placeholder: 'Select a Country',
            allowClear: true
        });

        $('#signup-form').validate({
            errorElement: 'div',
            errorClass: 'help-block text-danger',
            focusInvalid: true,

            rules: {
                password: {
                    passwordCheck: true
                },
                password_confirmation: {
                    equalTo: "#password"
                },
                email: {
                    emailCheck: true
                }
            },

            messages: {
                password: {
                    passwordCheck: "Minimum 8 or more characters, at least one uppercase letter, one lowercase letter, one number and one special character.",

                },
            });
            $.validator.addMethod("passwordCheck", function (value) {
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_.#])[A-Za-z\d@$!%*?&_.#]{8,}/.test(value)
            });
            $.validator.addMethod("emailCheck", function (value) {
                return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)
            });
        });
        $.validator.addMethod("passwordCheck", function(value) {
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/.test(value)
        });
        $.validator.addMethod("emailCheck", function(value) {
            return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)
        });
    });


    $(document).ready(function() {

        $('#term_and_condition').click(function(e) {
            if ($(this).data('is-link-open') == 0) {
                e.preventDefault();
                var url = $(this).data('term-and-condition-link');
                $('#term_and_condition').data('is-link-open', 1);
                window.open(url, '_blank');

            } 
        });


    });
</script>

@endsection