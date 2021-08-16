@extends('frontend.layouts.app')
@section('title', 'Pools')
@section('content')

<div class="container">
	<div class="card-group">
		<div class="card">
			<div class="card-header">
				Pool
			</div>
			<div class="card-body">
				@include('frontend.messages')
				@forelse($pools as $pool)
				<div class="card">
					<div class="card-body">
						<h5 class="card-title"><strong>{{$pool['name']}}</strong></h5>
						<span class="card-title">Min Deposite:<strong>{{$pool['min_deposits']}}</strong></span>
						<br>
						<span class="card-title">Max Deposite:<strong>{{$pool['max_deposits']}}</strong></span>
						<br>
						<span class="card-title">Profit Percentage:<strong>{{$pool['profit_percentage']}}%
						</strong></span>
						<br>
						<span class="card-title">Management Fee Percentage:<strong>{{$pool['management_fee_percentage']}}%
						</strong></span>
						<br>
						<span >Started Date:<strong>{{date('d M,Y', strtotime($pool['start_date']))}}</strong></span>
					</div>
					<div class="card-footer">
						<small class="text-muted">End Date: {{date('d M,Y', strtotime($pool['end_date']))}}</small>
						<a href="{{ url('/pools/' . Hashids::encode($pool->id)) }}" class="btn btn-xs btn-primary pull-right">View</a>
					</div>
				</div>
				@empty
		 		<p>No recorded are fund.</p>
			 	@endforelse
			</div>
		</div>
	</div>
</div>
@endsection
