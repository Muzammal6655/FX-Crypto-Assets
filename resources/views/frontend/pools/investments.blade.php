@extends('frontend.layouts.app')
@section('title', 'Pool Investments')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="mbl-card-header card-header d-flex align-items-center justify-content-between flex-lg-nowrap flex-md-nowrap flex-wrap">
				Pool Investments History
			</div>
			<div class="card-body general-table-des">
				<div class="table-responsive">
					@include('frontend.messages')
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th> 
								<th scope="col">Pool</th>
								<th scope="col">Amount ({{config('constants.currency')['symbol']}})</th>
								<th scope="col">Profit (%)</th>
								<th scope="col">Profit ({{config('constants.currency')['symbol']}})</th>
								<th scope="col">Fee (%)</th>
								<th scope="col">Start Date</th>
								<th scope="col">End Date</th>
								<th scope="col">Approved At</th>
								<th scope="col">Status</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							@php $count = $poolInvestments->firstItem();  @endphp
							@forelse($poolInvestments as $poolinvestment)
								<tr>
									<th scope="row">{{ $count++ }}</th>
									<td>{{ !empty($poolinvestment->pool_id) ? $poolinvestment->pool->name : '' }}</td>
									<td>{{number_format( $poolinvestment->deposit_amount,4)}}</td>
									<td>{{number_format($poolinvestment->profit_percentage,4) }}</td>
									<td>@if(!empty($poolinvestment->profit))
										 {{number_format($poolinvestment->profit,4)}} 
										@endif
									</td>
									<td>{{ number_format($poolinvestment->management_fee_percentage ,4)}}</td>
									<td>{{  !empty($poolinvestment->start_date	) ?  \Carbon\Carbon::createFromTimeStamp($poolinvestment->start_date)->tz(auth()->user()->timezone)->format('d M, Y') : ''}}</td>
									<td>{{ !empty($poolinvestment->start_date	) ?  \Carbon\Carbon::createFromTimeStamp($poolinvestment->end_date)->tz(auth()->user()->timezone)->format('d M, Y') : '' }}</td>
									<td>{{ !empty($poolinvestment->approved_at) ? \Carbon\Carbon::createFromTimeStamp(strtotime($poolinvestment->approved_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y')  : '' }}</td>
									<td>
										@if($poolinvestment->status == 0)
											<span class="badge bg-warning">Pending</span>
										@elseif($poolinvestment->status == 1)
											<span class="badge bg-success">Approved</span>
										@elseif ($poolinvestment->status == 2)
											<span class="badge bg-danger">Rejected</span>
										@endif
									</td>
									<td>
										<div class="d-flex flex-row align-items-center">
											@if($poolinvestment->status != 1)
												<a href="{{ url('/pool-investments/' . Hashids::encode($poolinvestment->id) .'/edit') }}" class="btn  btn-success mr-1"><i class="fa fa-edit"></i></a>
											@endif
												<a href="{{ url('/pool-investments/' . Hashids::encode($poolinvestment->id)) }}" class="btn btn-xs btn-primary pull-right">View</a>
										</div>
									</td>
								</tr>
							@empty
								<tr>
									<td>No records found!</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer text-center">
				{{ $poolInvestments->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
