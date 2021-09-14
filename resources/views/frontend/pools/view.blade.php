@extends('frontend.layouts.app')
@section('title', 'Pool')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card-group pool-view-des">


			<div class="card">
				<div class="card-body">
					<!-- <div class="row">
                            <div class="col-sm-3"> -->
					<h5 class="card-title card-top-title">{{$pool['name']}}</h5>
					@include('frontend.messages')
					<p class="card-title text-center mb-4">{{$pool['description']}}</p>
					<input type="hidden" class="form-control" name="pool_id" value="{{ $pool['id'] }}">
					<ul class="list-unstyled pool-body-info">
						<li class="d-flex">
							<span class="card-title">Days:</span>
							<span class="card-detail">{{$pool['days']}}</span>
						</li>
						<li class="d-flex">
							<span class="card-title">Wallet Address:</span>
							<span class="card-detail">{{$pool['wallet_address']}}</span>
						</li>
						<li class="d-flex">
							<span class="card-title">Min Deposit:</span>
							<span class="card-detail">{{number_format( $pool['min_deposits'],4)}}</span>
						</li>
						<li class="d-flex">
							<span class="card-title">Max Deposit:</span>
							<span class="card-detail">{{number_format( $pool['max_deposits'],4)}}</span>
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
					</ul>
				</div>
				<div class="card-footer">
					<a href="{{ url('/deposits/create/?pool_id=' . Hashids::encode($pool->id)) }}" class="btn btn-xs btn-primary pull-right">Deposit</a> 
					<a href="{{ url('/pools/' . Hashids::encode($pool->id)). '/invest' }}" class="btn btn-xs btn-yellow pull-right mr-2">Invest</a>
				</div>
			</div>

			
		</div>

		</br>
		@if (!empty($poolInvestmentsYvalues)) 
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title card-top-title">Pool Investments & Profit History</h5>
						<div class="col-sm-12">
							<canvas id="poolInvestmentsChart" style="width:100%;"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>


@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script>
	var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	var poolinvestmentsYvalues = JSON.parse("{{$poolInvestmentsYvalues}}");
	var poolInvestmentsDepositYvalues = JSON.parse("{{$poolInvestmentsDepositYvalues}}");
	new Chart("poolInvestmentsChart", {
		type: "line",
		data: {
			labels: xValues,
			datasets: [{
				label: 'Profit Amount',
				fill: false,
				lineTension: 0,
				backgroundColor: "#d0af3e",
				borderColor: "green",
				data: poolinvestmentsYvalues
			},
			{
				label: 'Investment Amount',
				fill: false,
				lineTension: 0,
				backgroundColor: "#d0af3e",
				borderColor: "blue",
				data: poolInvestmentsDepositYvalues
			}
			]
		},
		options: {
			legend: {
				display: false
			},
		}
	});
	</script
	@endsection