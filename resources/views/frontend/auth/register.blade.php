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
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    placeholder="Username">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Contact">
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control">
                                                  <option>Country</option>
                                                  <option>Country A</option>
                                                  <option>Country B</option>
                                                  <option>Country C</option>
                                                  <option>Country D</option>
                                                </select>
                                              </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    placeholder="How you know about FX ">
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
