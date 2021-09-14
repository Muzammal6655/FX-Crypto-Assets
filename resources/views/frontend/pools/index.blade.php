@extends('frontend.layouts.app')
@section('title', 'Pools')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card-group pool-page-des">
			<div class="card">
				<div class="card-header">
					Pool
				</div>
				<div class="card-body">
					@include('frontend.messages')
					<div class="row">
					@forelse($pools as $pool)
						<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
							<div class="card pool-body">
								<div class="card-body">
									<h5 class="card-title"><strong>{{$pool['name']}}</strong></h5>
									<ul class="list-unstyled pool-body-info">
										<li class="d-flex">
											<span class="card-title">Days:</span>
											<span class="card-detail">{{$pool['days']}}</span>
										</li>
										<li class="d-flex">
											<span class="card-title">Min Deposit:</span>
											<span class="card-detail">{{number_format( $pool['min_deposits'],2)}}</span>
										</li>
										<li class="d-flex">
											<span class="card-title">Max Deposit:</span>
											<span class="card-detail">{{number_format( $pool['max_deposits'],2)}}</span>
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
								<div class="card-footer text-center">
									<a href="{{ url('/pools/' . Hashids::encode($pool->id)) }}" class="btn btn-xs btn-primary">View</a>
								</div>
							</div>
						</div>
					@empty
					<p>No pools are available for deposit/investment.</p>
					@endforelse
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
