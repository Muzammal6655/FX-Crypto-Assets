@extends('frontend.layouts.app')
@section('title', 'Pool')
@section('content')
<div class="container">
	<div class="card-group">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title text-center">{{$pool['name']}}</h5>
				<h5 class="card-title">{{$pool['description']}}</h5>
				<span class="card-title">Wallet Address:<strong>{{$pool['wallet_address']}}
				</strong></span>
				<br>
 	 			<span class="card-title">Min Deposite:<strong>{{$pool['min_deposits']}}
				</strong></span>
 	 			<br>
 	 			<span class="card-title">Max Deposite:<strong>{{$pool['max_deposits']}}
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
			</div>
		</div>
	</div>
</div>
@endsection
@section('js')
@endsection