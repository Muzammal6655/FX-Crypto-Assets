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
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link" href="{{ url('/login') }}">Log In</a>
                            <a class="nav-link active" href="{{ url('/register') }}">Sign Up</a>
                        </div>
                    </div>
                    <div class="col-lg-7 right">
                        <div class="content-wrapper">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-signup" role="tabpanel"
                                    aria-labelledby="v-pills-signup-tab">
                                    <div class="form-wrapper">
                                        <h2>Join Interesting FX</h2>
                                        <form class="text-left">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="First Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Family Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" placeholder="Confirm Password">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Mobile Number">
                                            </div>
                                            <div class="form-group">
                                                <input type="date" class="form-control" placeholder="Date of Birth">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Address">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Suburb">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="State">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Country">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Emergency ID Verification Code">
                                            </div>

                                            <p>Do you an Existing BTC wallet for withdrawals?</p>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="BTCOptions"value="yes" id="inlineRadio1">
                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="BTCOptions"value="no" id="inlineRadio2">
                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                            </div>

                                            <div class="form-group" id="btc_wallet_address" style="display: none">
                                                <input type="text" class="form-control" placeholder="BTC Wallet Address">
                                            </div>

                                            <p id="binance" style="display: none;"><a href="https://www.binance.com/en/register?ref=CBPE2Z8R" target="_blank">Binance</a> - Interesting FX is paid a referral fee for referring our customers to Binance. Interesting FX does not require you to use Binance we offer this link purely as a service.</p>

                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input">
                                                <label class="form-check-label">I have read and agree to the <a href="{{ url('/pages/terms/') }}" target="_blank">T&C</a></label>
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
            $('input[name=BTCOptions]').change(function() {
                if (this.value == 'yes') {
                    $('#btc_wallet_address').show();
                    $('#binance').hide();
                }
                else if (this.value == 'no') {
                    $('#btc_wallet_address').hide();
                    $('#binance').show();
                }
            });

            $('#country').select2(
            {
                placeholder: 'Select a Country',
                allowClear: true
            });

            $('#signup-form').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: false,

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
                        passwordCheck: "Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters",
                    },
                    email: {
                        emailCheck: "Please enter a valid email address."
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

        function signUpEnable(ele) {
            if ($("input[type=checkbox]").is(
                ":checked")) {
                $('#signupButton').attr('disabled', false);
            } else {
                $('#signupButton').attr('disabled', true);
            }
        }

    </script>

@endsection
