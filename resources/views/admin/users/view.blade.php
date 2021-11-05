@extends('admin.layouts.app')

@section('title', 'Customers')
@section('sub-title', $action.' customer')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/customers')}}"><i class="fa fa-user"></i>Customers</a></li>
			<li>{{$action}}</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">{{$action}} Customer</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal label-left">

							<h4 class="heading">Basic Information</h4>

							<div class="form-group">
								<label for="name" class="col-sm-3 control-label">First Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->name }}">
								</div>
							</div>

							<div class="form-group">
								<label for="family_name" class="col-sm-3 control-label">Family Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->family_name }}">
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->email }}">
								</div>
							</div>

							<div class="form-group">
								<label for="mobile_number" class="col-sm-3 control-label">Mobile Number</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->mobile_number }}">
								</div>
							</div>

							<div class="form-group">
								<label for="dob" class="col-sm-3 control-label">Date of Birth</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->dob }}">
								</div>
							</div>

							<div class="form-group">
								<label for="btc_wallet_address" class="col-sm-3 control-label">BTC Wallet Address</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->btc_wallet_address }}">
								</div>
							</div>

							<!-- <div class="form-group">
								<label for="emergency_id_verification_code" class="col-sm-3 control-label">Emergency Id Verification Code</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->emergency_id_verification_code }}">
								</div>
							</div> -->

							<div class="form-group">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									@if($user->status == 0)
										<span class="label label-warning">Disable</span>
									@elseif($user->status == 1)
										<span class="label label-success">Active</span>
									@elseif($user->status == 2)
										<span class="label label-primary">Unverified</span>
									@elseif($user->status == 3)
										<span class="label label-danger">Deleted</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Approval Status</label>
								<div class="col-sm-9">
									@if($user->is_approved == 0)
										<span class="label label-warning">Pending</span>
									@elseif($user->is_approved == 1)
										<span class="label label-success">Approved</span>
									@elseif($user->is_approved == 2)
										<span class="label label-danger">Rejected</span>
									@endif
								</div>
							</div>

							<hr>

							<h4 class="heading">Referral Information</h4>

							<div class="form-group">
								<label for="invitation_code" class="col-sm-3 control-label">Invitation Code</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->invitation_code }}">
								</div>
							</div>

							<hr>

							<h4 class="heading">Account Information</h4>

							<div class="form-group">
								<label for="account_balance" class="col-sm-3 control-label">Account Balance ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->account_balance }}">
								</div>
							</div>

							<div class="form-group">
								<label for="deposit_total" class="col-sm-3 control-label">Deposit Total ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->deposit_total }}">
								</div>
							</div>

							<div class="form-group">
								<label for="withdraw_total" class="col-sm-3 control-label">Withdraw Total ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->withdraw_total }}">
								</div>
							</div>

							<div class="form-group">
								<label for="commission_total" class="col-sm-3 control-label">Commission Total ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->commission_total }}">
								</div>
							</div>

							<div class="form-group">
								<label for="profit_total" class="col-sm-3 control-label">Profit Total ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->profit_total }}">
								</div>
							</div>

							<hr>

							<h4 class="heading">Address Information</h4>

							<div class="form-group">
								<label for="street" class="col-sm-3 control-label">Address</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->street }}">
								</div>
							</div>

							<div class="form-group">
								<label for="city" class="col-sm-3 control-label">Suburb</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->city }}">
								</div>
							</div>

							<!-- <div class="form-group">
								<label for="postcode" class="col-sm-3 control-label">Zip Code</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->postcode }}">
								</div>
							</div> -->

							<div class="form-group">
								<label for="state" class="col-sm-3 control-label">State</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->state }}">
								</div>
							</div>

							<div class="form-group">
								<label for="country" class="col-sm-3 control-label">Country</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->country->name }}">
								</div>
							</div>
							
							<hr>
							<h4 class="heading">Security Questions and Answers </h4>
							@foreach ($security_questions as $seq_q)
							<div class="form-group">
								 <p><strong class="col-sm-3 control-label">{{$seq_q->securityquestion->question}}</strong></p>	
								 	<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{$seq_q->answer}}">
								</div> 
							</div>
							@endforeach

							<div class="text-right">
								<a href="{{url('admin/customers')}}">
									<button type="button" class="btn btn-primary btn-fullrounded">
										<span>Back</span>
									</button>
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
