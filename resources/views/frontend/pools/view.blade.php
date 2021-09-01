@extends('frontend.layouts.app')
@section('title', 'Pool')
@section('content')

<div class="container">
	<div class="card-group">
		<div class="card">
			<div class="card-body">
				@include('frontend.messages')
				<h5 class="card-title text-center">{{$pool['name']}}</h5>
				<p class="card-title">{{$pool['description']}}</p>
				<input type="hidden" class="form-control" name="pool_id" value="{{ $pool['id'] }}">
				<span class="card-title">Days:<strong>{{$pool['days']}}</strong></span>
				<br>
				<span class="card-title">Wallet Address:<strong>{{$pool['wallet_address']}}
				</strong></span>
				<br>
 	 			<span class="card-title">Min Deposit:<strong>{{$pool['min_deposits']}}
				</strong></span>
 	 			<br>
 	 			<span class="card-title">Max Deposit:<strong>{{$pool['max_deposits']}}
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
			</div>
			<div class="card-footer">
				<small class="text-muted">End Date: {{date('d M,Y', strtotime($pool['end_date']))}}</small>
				<a href="{{ url('/deposits/create/?pool_id=' . Hashids::encode($pool->id)) }}" class="btn btn-xs btn-primary pull-right">Deposit</a> 
				<a href="{{ url('/pools/' . Hashids::encode($pool->id)). '/invest' }}" class="btn btn-xs btn-primary pull-right">Invest</a>
			</div>
		</div>
	</div>
</div>
@endsection
