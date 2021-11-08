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
                                           <!--  <a href="{{url('otp-auth/disable-two-factor-authentication')}}">
                                                <button type="button" class="btn btn-primary btn-fullrounded">Disable</button>
                                            </a> -->
                                        <p>Please Select the CheckBox to disable the 2FA.</p>
                                        
                                        <form action="{{ url('otp-auth/disable-two-factor-authentication') }}" enctype="multipart/form-data" class="2form" method="POST" >
                                        {{ csrf_field() }}
                                        <!-- <input type="checkbox" class="child" name="radio-group" checked> -->
                                       <div style=" display: flex; justify-content: space-evenly;">
                                        <input type="checkbox" name="checkbox" id="checkbox-email" value="1"  /> 
                                        <label for="Email" style="margin-top: -4px;">Email</label>
                                        <br>
                                        <input type="checkbox" name="checkbox" id="checkbox-2fa-code" 
                                         value="2"/> 
                                        <label for="2FA Code" style="margin-top: -4px;">2FA Code</label>
                                        <br>
                                        <input type="checkbox" name="checkbox" id="checkbox-both" value="both" /> 
                                        <label for="both" style="margin-top: -4px;"  value="3"> Both</label>
                                       </div>
                                        <br>
                                        <input id="showthis" class="showthis" name="email_code" type="text" placeholder="Enter the Email Code" style="margin-bottom: 10px;padding: 8px 17px;margin-right: 15px;font-size: 14px;"  /> 
                                        <input id="showthis2FA"  class="showthis" name="two_fa_code"  
                                         type="text"  placeholder="Enter the 2FA Code"   style="padding: 8px 17px;font-size: 14px;"  /> 
                                          <br> 
                                           <button class="btn btn-outline-warning showthis2FA" type="button" id="generate_otp" style="margin-bottom: 35px;">Generate OTP <i class="fa fa-spinner fa-spin" id="generate_otp_loading" style="display: none;"></i></button>
                                           <br> 
                                         <button type="submit" id="disable" class="btn-theme">Disable</button>
                                        
                                        </form>   
                                        <!-- <button class="btn btn-outline-warning showthis2FA" type="button" id="generate_otp">Generate OTP <i class="fa fa-spinner fa-spin" id="generate_otp_loading" style="display: none;"></i></button>

                                        <input id="showthis" class="showthis" name="email_code" type="text" placeholder="Enter the Email Code"  /> 
                                        <input id="showthis2FA"  class="showthis" name="two_fa_code"  
                                         type="text"  placeholder="Enter the 2FA Code" style="display: inline-block;margin-top: 10px;margin-left: 132px;margin-bottom: 10px;" /> 
                                          <br>  
                                        -->
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
@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
 $(function () {
        $('.showthis').hide(); 
        $('#generate_otp').hide();
        $('#disable').attr("disabled", true);
        $('#checkbox-email').on('click', function () {
            if($('#checkbox-email').is(':checked')){
                $('#showthis').show();
                $('.showthis2FA').show();
                $("#checkbox-2fa-code").attr("disabled", true);
                $('#disable').attr("disabled", false);
            }
            else
            {
                $('#showthis').hide();
                $('#disable').attr("disabled", true);
                $('.showthis2FA').hide();
                $("#checkbox-2fa-code").attr("disabled", false);
            }
        });
         $('#checkbox-2fa-code').on('click', function () {
            if($('#checkbox-2fa-code').is(':checked')){
            $('#showthis2FA').show();
            $("#checkbox-email").attr("disabled", true);
            $('#disable').attr("disabled", false);
            }else{
            $('#showthis2FA').hide();
            $("#checkbox-email").attr("disabled", false);
            $("#checkbox-2fa-code").attr("disabled", false);
            $('#disable').attr("disabled", true);

            }
        });
          $('#checkbox-both').on('click', function () {
            if($('#checkbox-both').is(':checked')){
            $('#showthis').show();
            $('.showthis2FA').show();
            $('#checkbox-email').prop('checked', true);
            $('#checkbox-2fa-code').prop('checked', true);
            $("#checkbox-email").attr("disabled", false);
            $('#disable').attr("disabled", false);
            $("#checkbox-2fa-code").attr("disabled", false);
            $('#showthis2FA').show();
            }else{
            $('#disable').attr("disabled", true);
            $('#showthis').hide();
            $('.showthis2FA').hide();
             $('#checkbox-email').prop('checked', false);
            $('#checkbox-2fa-code').prop('checked', false);
            $('#showthis2FA').hide();

            }
        });
    });
  $("#generate_otp").click(function(){
            $('#generate_otp_loading').show();
            $('#generate_otp').prop('disabled',true);

            $.ajax({
                url: "{{ url('/otp-auth/send-email-code?type=deposit_request') }}",
                type: 'GET',
                success: function(res) {
                    $('#generate_otp_loading').hide();
                    $('#generate_otp').prop('disabled',false);
                     $('#disable').attr("disabled", false);
                    alert(res);
                }
            });
        });
</script>
<style>
    .2form{
        display: flex;
        justify-content: space-evenly;
    }
</style>

@endsection