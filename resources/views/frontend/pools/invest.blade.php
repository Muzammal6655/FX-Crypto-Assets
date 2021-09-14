@extends('frontend.layouts.app')
@section('title', 'Invest')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card-group pool-invest-group">
			<div class="card">
				@include('frontend.messages')
				<div class="card-body">
					<h5 class="card-title text-center pool-title">{{$pool['name']}}</h5>
					<p class="card-title text-center mb-4">{{$pool['description']}}</p>

					<ul class="list-unstyled pool-body-info">
						<li class="d-flex">
							<span class="card-title">Min Deposit:</span>
							<span class="card-detail">{{number_format($pool['min_deposits'],4)}}({{config('constants.currency')['symbol']}})</span>
						</li>
						<li class="d-flex">
							<span class="card-title">Max Deposit:</span>
							<span class="card-detail">{{number_format($pool['max_deposits'],4)}}({{config('constants.currency')['symbol']}})</span>
						</li>
						<li class="d-flex">
							<span class="card-title">Profit Percentage:</span>
							<span class="card-detail">{{$pool['profit_percentage']}}%</span>
						</li>
						<li class="d-flex">
							<span class="card-title">Management Fee Percentage:</span>
							<span class="card-detail">{{$pool['management_fee_percentage']}}%</span>
						</li>
						<li class="d-flex">
							<span class="card-title">Started Date:</span>
							<span class="card-detail">{{date('d M,Y', strtotime($pool['start_date']))}}</span>
						</li>
						<li class="d-flex">
							<span class="card-title">End Date:</span>
							<span class="card-detail">{{date('d M,Y', strtotime($pool['end_date']))}}</span>
						</li>
						<li class="d-flex">
							<span class="card-title">User Total Balance:</span>
							<span class="card-detail">{{number_format($user->account_balance,4)}}({{config('constants.currency')['symbol']}})</span>
						</li>
					</ul>

					<form id="invest-form"  method="POST" action="{{url('/invest')}}">
						{{ csrf_field() }}
						<input type="hidden" class="form-control" name="pool_id" value="{{ $pool['id'] }}">
						<input type="hidden" class="form-control" name="user_id" value="{{ $user['id'] }}">
						<input name="id" type="hidden" value="{{ $model->id }}" />
						<input type="hidden" name="action" value="{{$action}}" />
						<div class="form-group">
							<br>
							<label for="Invest_amount">Enter Amount</label>
							<input 
								type="number" 
								class="form-control" 
								name="invest_amount" 
								min="{{$pool->min_deposits}}" 
								max="{{$user->account_balance >= $pool->max_deposits ? $pool->max_deposits : $user->account_balance}}" 
								value="{{ ($action == 'Edit') ? $model->deposit_amount : old('invest_amount')}}"
								placeholder="Enter the amount" 
								required=""
							>
						</div>
						@if($user->email_otp_status == 1)
						<h5 class="mb-4">OTP Verification</h5>
						<div class="form-group">
							<label for="email_code">
								Email Code
								<button class="btn btn-outline-warning ml-2" type="button" id="generate_otp">Generate OTP <i class="fa fa-spinner fa-spin" id="generate_otp_loading" style="display: none;"></i></button>
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
						<div class="card-footer pl-0">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
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