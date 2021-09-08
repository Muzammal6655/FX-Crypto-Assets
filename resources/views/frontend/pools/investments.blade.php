@extends('frontend.layouts.app')
@section('title', 'Pool Investments')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="card-header">
				Pool Investments History
			</div>
			<div class="card-body">
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
								<td>{{ $poolinvestment->deposit_amount }}</td>
								<td>{{ $poolinvestment->profit_percentage }}</td>
								<td>{{number_format($poolinvestment->profit,2)}}</td>
								<td>{{ $poolinvestment->management_fee_percentage }}</td>
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
								@if($poolinvestment->status != 1)
									<a href="{{ url('/pool-investments/' . Hashids::encode($poolinvestment->id) .'/edit') }}" class="btn  btn-success"><i class="fa fa-edit"></i></a>
								@endif
									<a href="{{ url('/pool-investments/' . Hashids::encode($poolinvestment->id)) }}" class="btn btn-xs btn-primary pull-right">View</a>
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
			<div class="card-footer text-center">
				{{ $poolInvestments->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
