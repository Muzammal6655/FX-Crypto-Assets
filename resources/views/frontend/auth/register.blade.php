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
                            <a href="{{ url('/') }}"><img src="{{asset(env('PUBLIC_URL').'images/logo.svg')}}" alt="" class="img-fluid" /></a>
                        </div>
                        <p>Join the world's largest crypto exchange</p>
                    </div>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link mr-lg-0 mr-1" href="{{ url('/login') }}">Log In</a>
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
                                            <input type="text" class="form-control" name="name" maxlength="100" placeholder="First Name *" value="{{old('name')}}" required="required">

                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="family_name" maxlength="30" placeholder="Family Name *" value="{{old('family_name')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" maxlength="100" placeholder="Email *" value="{{old('email')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <span class="fa fa-fw fa-eye-slash password-field-icon toggle-password"></span>
                                            <input type="password" id="password" class="form-control" name="password" placeholder="Password *" value="{{old('password')}}" minlength="8" maxlength="30" required="required">
                                        </div>
                                        <div class="form-group">
                                            <span class="fa fa-fw fa-eye-slash password-field-icon toggle-password"></span>
                                            <input type="password" class="form-control" placeholder="Confirm Password *" name="password_confirmation" value="{{old('password')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Mobile Number+61 xxxxxxxxx 04xxxxxxxx  +61xxxxxxxxx*" name="mobile_number" value="{{old('mobile_number')}}" minlength="12" maxlength="20" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Date of Birth (DD-MM-YYYY)*" name="dob" id="my_date_picker" value="{{old('dob')}}" required="required" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Address *" name="street" value="{{old('street')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Suburb *" name="city" value="{{old('city')}}" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="State *" name="state" value="{{old('state')}}" required="required">
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
                                        <p><b>Please select three security questions below. These questions will help us to verify your identity in case of forgetting password. Please remember answers are case sensitive. </b></p>
                                        @for($i=0;$i<=2;$i++) <div class="form-group">
                                            <select class="form-control questions" name="question_id{{$i}}" id="question_id{{$i}}">
                                                <option>Select your Questions</option>
                                                @foreach ($security_questions as $security_question)
                                                <option value="{{$security_question->id}}">{{$security_question->question}}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control" placeholder="Answer*" name="answer{{$i}}" value="{{old('answer')}}" required="required">
                                </div>
                                @endfor

                                <p><strong>Were you referred to Interesting FX? *</strong></p>

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
                                    <input type="text" name="referral_code" class="form-control" placeholder="Referral Code *" value="{{old('referral_code') ?? $referral_code}}">
                                </div>

                                <div class="form-check referral-code" style="display: {{ (old('ReferredOptions') == 'yes' || !empty($referral_code)) ? '' : 'none' }}">
                                    <input type="checkbox" class="form-check-input" name="provide_later" id="provide_later" {{ old('provide_later') == 'on' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="provide_later">Provide Later</label>
                                </div>

                                <p id="provide_later_text" style="display: {{ old('provide_later') == 'on' ? '' : 'none' }}">You have till the last day of next month to provide Referral Code.</p>

                                <p><strong>Do you have an Existing BTC wallet for withdrawals? *</strong></p>

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
                                    <input type="text" name="btc_wallet_address" class="form-control" placeholder="BTC Wallet Address" value="{{old('btc_wallet_address')}}" minlength="42" maxlength="42">

                                     <input type="text" name="memo_address" class="form-control" placeholder="Memo Address" value="{{old('memo_address')}}" minlength="42" maxlength="42">
                                </div>

                                <p id="binance" style="display: {{ old('BTCOptions') == 'no' ? '' : 'none' }};"><a href="https://www.binance.com/en/register?ref=CBPE2Z8R" target="_blank">Binance</a> - Interesting FX is paid a referral fee for referring our customers to Binance. Interesting FX does not require you to use Binance we offer this link purely as a service.</p>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input data-is-link-open=0 data-term-and-condition-link="{{config('constants.wordpress_base_url')}}terms/" id="term_and_condition" type="checkbox" name="agree" class="form-check-input" required="" {{ old('agree') == 'on' ? 'checked' : '' }}>

                                        <label class="form-check-label">I have read and agree to the <a href="{{config('constants.wordpress_base_url')}}terms/" target="_blank">T&C</a></label>
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
                name: {
                    spaceCheckWithAlphabet: true
                },
                family_name: {
                    spaceCheckWithAlphabet: true
                },
                street: {
                    spaceCheck: true
                },
                city: {
                    spaceCheckWithAlphabet: true
                },
                state: {
                    spaceCheckWithAlphabet: true
                },
                password: {
                    passwordCheck: true
                },
                password_confirmation: {
                    equalTo: "#password"
                },
                email: {
                    emailCheck: true
                },
                mobile_number: {
                    minlength: 8,
                    maxlength: 30,
                    mobileCheck: true
                },
                emergency_id_verification_code: {
                    spaceCheck: true
                },
                btc_wallet_address: {
                    minlength: 42,
                    maxlength: 42,
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
                email: {
                    emailCheck: "Please enter a valid email address."
                },
                mobile_number: {
                    mobileCheck: "Please enter a valid mobile number e.g +61 123456789."
                },
                emergency_id_verification_code: {
                    spaceCheck: "Please enter valid address",

                },
                btc_wallet_address: {
                    walletAddressCheck: "Minimum 42 characters and no special character are used."
                },
            },

            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error');
                $(e).remove();
            },
            errorPlacement: function(error, element) {
                if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('.form-group');
                    if (controls.find(':checkbox,:radio').length >= 1) {
                        controls.append(error);
                    } else {
                        error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    }
                } else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                } else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                } else {
                    //error.insertAfter(element.parent());
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(form, validator) {
                // $('html, body, #v-pills-tabContent').animate({
                //     scrollTop: $(validator.errorList[0].element).offset().top - 70
                // }, 500);
            },
        });
        $.validator.addMethod("spaceCheckWithAlphabet", function(value) {
            return /^[a-zA-Z][a-zA-Z]+/.test(value)
        });
        $.validator.addMethod("spaceCheck", function(value) {
            return /^[^\s].+[^\s]/.test(value)
        });
        $.validator.addMethod("passwordCheck", function(value) {
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_.#])[A-Za-z\d@$!%*?&_.#]{8,}/.test(value)
        });
        $.validator.addMethod("emailCheck", function(value) {
            return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)
        });
        $.validator.addMethod("mobileCheck", function(value) {
            // return /^(([+][(]?[0-9]{1,3}[)]?))\s*[)]?[-\s\.]?[(]?[0-9]{1,3}[)]?([-\s\.]?[0-9]{3})([-\s\.]?[0-9]{3,4})$/.test(value) 

            return /^(([(]?[+0-9]{1,3}[)]?))\s*[)]?[-\s\.]?[(]?[0-9]{1,3}[)]?([-\s+\.]?[0-9]{3})([-\s+\.]?[0-9\+]{3,4})$/.test(value)


            // return /^((\+|00)[1-9]{1,3})?(\-| {0,1})?(([\d]{0,3})(\-| {0,1})?([\d]{5,11})){1}$/.test(value) 


        });
        $.validator.addMethod("walletAddressCheck", function(value) {
            return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[0-9])[A-Za-z0-9\d]{42,}/.test(value)
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

    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $(this).siblings('input');
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });


    $(function() {
        $("#my_date_picker").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            // yearRanger : "-100",
            yearRange: "-800:+0",
            minDate: new Date(1220, 1 - 1),
            maxDate: '-1D',
        });
    });



    $(document).ready(function() {
        var selectState = {
            'question_id0': 'null',
            'question_id1': 'null',
            'question_id2': 'null'
        };

        $('.questions').change(function() {
            var selectId = $(this).attr('id');
            var selectedOptionValue = $(this).val();

            // for each other select element
            $('select[id!="' + selectId + '"]').each(function(index) {
                // enable the old option
                $(this).find('option[value="' + selectState[selectId] + '"]').removeAttr('disabled');

                if (selectedOptionValue !== 'null') { // if selected a real option
                    // disable the new option
                    $(this).find('option[value="' + selectedOptionValue + '"]').attr('disabled', 'disabled');
                }
            });

            selectState[selectId] = selectedOptionValue; // update the new state at the end
        });
    })
</script>

@endsection