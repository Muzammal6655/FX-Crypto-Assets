@extends('frontend.layouts.app')
@section('title', 'Invest')
@section('content')

<div class="container">
	<div class="card-group">
		<div class="card">
			@include('frontend.messages')
			<div class="card-body">
				<h5 class="card-title text-center">{{$pool['name']}}</h5>
				<p class="card-title">{{$pool['description']}}</p>
				<span class="card-title">Min Deposit:<strong>{{$pool['min_deposits']}}({{config('constants.currency')['symbol']}})
				</strong></span>
				<br>
				<span class="card-title">Max Deposit:<strong>{{$pool['max_deposits']}}({{config('constants.currency')['symbol']}})
				</strong></span>
				<br>
				<span class="card-title">Profit Percentage:<strong>{{$pool['profit_percentage']}}%
				</strong></span>
				<br>
				<span class="card-title">Management Fee Percentage:<strong>{{$pool['management_fee_percentage']}}%
				</strong></span>
				<br>
				<span class="card-title">Started Date:<strong>{{date('d M,Y', strtotime($pool['start_date']))}}
				</strong></span>
				<br>
				<span class="card-title">End Date:<strong> {{date('d M,Y', strtotime($pool['end_date']))}}
				</strong></span>
				<br>
				<span class="card-title">User Total Balance:<strong>
				{{number_format($user->account_balance,2)}}({{config('constants.currency')['symbol']}})
				</strong></span>
				<form id="invest-form"  method="POST" action="{{url('/invest')}}">
					{{ csrf_field() }}
					<input type="hidden" class="form-control" name="pool_id" value="{{ $pool['id'] }}">
					<input type="hidden" class="form-control" name="user_id" value="{{ $user['id'] }}">
					<div class="form-group">
						<br>
						<label for="Invest_amount">Enter Amount</label>
						<input 
							type="number" 
							class="form-control" 
							name="invest_amount" 
							min="{{$pool->min_deposits}}" 
							max="{{$user->account_balance >= $pool->max_deposits ? $pool->max_deposits : $user->account_balance}}" 
							placeholder="Enter the amount" 
							required=""
						>
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
					<div class="card-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
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
		$('#invest-form').validate({
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
		        url: "{{ url('/otp-auth/send-email-code?type=investment_request') }}",
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