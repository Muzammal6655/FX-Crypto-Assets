@extends('frontend.layouts.app')
@section('title', 'Forgot Email')

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
                                <div class="tab-pane fade show active" id="v-pills-forget" role="tabpanel"
                                    aria-labelledby="v-pills-forget-tab">
                                    <div class="form-wrapper">
                                        <h2>Update Email</h2>
                                        @include('frontend.messages')
                                        <form id="update_email_send" class="text-right"
                                            method="POST" action="{{ url('/email') }}">
                                            @csrf
                                            <div class="form-group">
                                            <input type="email" class="form-control" name="update_email" placeholder="Enter the new Email" id="update_email" required >
                                              <p id="update_email_error"></p>
                                            </div>
                                            <div class="form-group">
											<input type="number" class="form-control" id="new_email" name="otp_code" value="{{ old('email_code') }}" placeholder="Enter the OTP Code" minlength="6" maxlength="6" required="">
											</div>
											<label for="email_code">
											<button id="update_email_send_code" class="btn btn-outline-warning generate_otp" type="submit"  >Generate OTP <i class="fa fa-spinner fa-spin" id="generate_otp_loading" style="display: none;"></i></button>
											</label>
                                            <div class="btn-wrap">
                                                <button type="submit" id="update_email_btn" class="btn-theme text-capitalize">Update Email
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
        $(function(){
            $('#update_email_send').validate({
                errorElement: 'div',
                errorClass: 'help-block text-danger',
                focusInvalid: false,

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

            $('#update_email_btn').hide();
            $(".generate_otp").click(function(){
            $('#generate_otp_loading').show();
        	$('.generate_otp').prop('disabled',true);
		    $('#update_email_btn').hide();
		     	
		    var emailaddressVal = $("#update_email").val();
		        if(!emailaddressVal || emailaddressVal == '') {
		        	$("#update_email").focus();
		        	$("#update_email_error").html("Please enter your email address.").addClass("has-error");
		        	$('#generate_otp_loading').hide();	
		        }
		    	else
        		{	 
        		 let name = $("input[name=update_email]").val();
        		 $.ajax({
        		        url: "{{ url('/update-email/send-email-code?type=send_otp') }}",
        		        type: 'POST',
        		         data:{
				          name:name,
				        },
        		        success: function(res) {
        		        		 $("#update_email").prop("readonly", true);
        		        	 	  $('#generate_otp_loading').hide();
		        		    	  $('#update_email_btn').show();
		        		    	  $('.generate_otp').hide();	
		        		          $('.generate_otp').prop('disabled',false);
		        		          alert(res);
        		        },
        		         error: function(error) {
				         console.log(error);
				        }
            		});
				}
    		
			});

			$( "#update_email" ).change(function() {
			  	$("#update_email_send_code").attr('disabled', false);
			  	$("#generate_otp_loading").attr('disabled', false);
			  	$('#update_email_error').hide();

			});

// 		$(document).ready(function() {

//   		$("#update_email_send").submit(function(e) {
//  			alert(awais);
// 		    e.preventDefault();
// 		    var update_email = $('#update_email').val(),
// 		    $.ajax({
// 		      type: "GET",
// 		      url: "{{ url('/otp-auth/send-email-code?type=deposit_request') }}",
// 		      data: "update_email=" + update_email,
// 		      success: function(html) {
// 		        console.log(html);
// 		      }
// 		    });
// 		    return false;

// 		  });
// });



















        });
    </script>

@endsection
