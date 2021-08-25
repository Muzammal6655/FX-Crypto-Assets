@extends('frontend.layouts.app')
@section('title', 'Create Withdraw')
@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Create Withdraw
		</div>
		<div class="card-body">
			<h5 class="card-title">Fill the form below to create withdraw</h5>
			@include('frontend.messages')
			<form id="withdraws-form" method="POST" action="{{url('/withdraws')}}" enctype="multipart/form-data">
				{{ csrf_field() }}

				<div class="form-group">
					<label for="wallet_address">Wallet Address</label>
					<input type="text" class="form-control" id="wallet_address" value="{{$wallet_address}}" readonly="">
				</div>
				<div class="form-group">
					<label for="account_balance">Account Balance ({{config('constants.currency')['symbol']}})</label>
					<input type="number" class="form-control" value="{{ $user->account_balance }}" id="account_balance" readonly="">
				</div>
				<div class="form-group">
					<label for="amount">Amount of BTC</label>
					<input type="number" class="form-control" min="0.00000001" max="{{ $user->account_balance }}" id="amount" name="amount" value="{{ old('amount') }}" required="">
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
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	$(function(){
        $('#withdraws-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: true,
            
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
	            } 
	            else if (element.is('.select2')) {
	            	error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
	            } 
	            else if (element.is('.chosen-select')) {
	            	error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
	            } 
	            else
                    error.insertAfter(element);
            },
            invalidHandler: function (form,validator) {
            }
        });

        $("#generate_otp").click(function(){
        	$('#generate_otp_loading').show();
        	$('#generate_otp').prop('disabled',true);

        	$.ajax({
		        url: "{{ url('/otp-auth/send-email-code?type=withdraw_request') }}",
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
