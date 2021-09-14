@extends('frontend.layouts.app')
@section('title', 'Create Deposit')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="card-header">
			{{ ($action == 'Add') ? 'Create Deposit' : 'Edit Deposit' }}
			</div>
			<div class="card-body">
				<h5 class="card-title">Fill the form below to create deposit</h5>
				@include('frontend.messages')
				<div class="wallet-address-box d-flex flex-lg-row flex-column align-items-center">
					<label for="wallet_address" class="mb-lg-0 mb-md-2">Recipient Wallet Address:</label>
					<p class="mb-lg-0 mb-md-5">{{$wallet_address}}</p>
				</div>
				<form id="deposits-form" method="POST" action="{{url('/deposits')}}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" class="form-control" name="pool_id" value="{{ $pool_id }}">
					<input type="hidden" name="action" value="{{$action}}" />
					<input name="id" type="hidden" value="{{ $model->id }}" />
					@if(!empty($pool_name))
						<div class="form-group">
							<label for="pool_name">Pool Name</label>
							<input type="text" class="form-control" id="pool_name" name="pool_name" value="{{$pool_name}}" readonly="">
						</div>
						<div class="form-group">
							<label for="pool_max_deposit">Pool Max Deposit</label>
							<input type="text" class="form-control" id="pool_max_deposit"   value="{{ number_format($max_deposits,2) }}" readonly="">
						</div>
						<div class="form-group">
							<label for="pool_min_deposit">Pool Min Deposit</label>
							<input type="text" class="form-control" id="pool_min_deposit"  value="{{ number_format($min_deposits,2) }}" readonly="">
						</div>
					@endif
					<div class="form-group">
					
						<input type="hidden" class="form-control" id="wallet_address" name="wallet_address" value="{{$wallet_address}}" readonly="">
					</div>
					<div class="form-group">
						<label for="amount">Amount of BTC</label>
						<input type="number" class="form-control" min="{{ $min_deposits }}" max="{{ $max_deposits }}" minlength="1" maxlength="8" id="amount" name="amount"
						value="{{ ($action == 'Edit') ? $model->amount : old('amount')}}" required="">
					</div>
					<div class="form-group">
						<label for="transaction_id">Transaction Id</label>
						<input type="text" class="form-control" id="transaction_id" name="transaction_id"  value="{{ ($action == 'Edit') ? $model->transaction_id : old('transaction_id')}}"  required="">
					</div>
					<div class="form-group">
						<label for="proof">Deposit Receipt</label>
						<input type="file" class="form-control" id="proof" name="proof" accept="image/*" required="">
					</div>
					@if($user->email_otp_status == 1)
					<h5 class="mb-4">OTP Verification</h5>
					<div class="form-group">
						<label for="email_code">
							Email Code
							<button class="btn btn-outline-warning" type="button" id="generate_otp">Generate OTP <i class="fa fa-spinner fa-spin" id="generate_otp_loading" style="display: none;"></i></button>
						</label>
						<input type="number" class="form-control" id="email_code" name="email_code" value="{{ old('email_code') }}" minlength="6" maxlength="6" required="">
					</div>
					@endif
					@if($user->otp_auth_status == 1)
					<div class="form-group">
						<label for="two_fa_code">2FA Code</label>
						<input type="number" class="form-control" id="two_fa_code" name="two_fa_code" value="{{ old('two_fa_code') }}" required="">
					</div>
					@endif
					<!-- <button type="submit" class="btn btn-primary">Submit</button> -->
					<div class="btn-wrap">
						<button type="submit" class="btn btn-primary text-capitalize">Submit
						</button>
                    </div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	$(function(){
        $('#deposits-form').validate({
            errorElement: 'div',
            errorClass: 'help-block text-danger',
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
		        url: "{{ url('/otp-auth/send-email-code?type=deposit_request') }}",
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
