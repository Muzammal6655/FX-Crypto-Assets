@extends('frontend.layouts.app')
@section('title', 'View Withdraws')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="mbl-card-header card-header d-flex align-items-center justify-content-between flex-lg-nowrap flex-md-nowrap flex-wrap">
				Withdraw View
				<span class="pull-right">
					<a href="{{url('/withdraws/create')}}" class="btn btn-success mt-lg-0 mt-md-0 mt-sm-0 mt-2">Create Withdraw</a>
				</span>
			</div>
			<div class="card-body general-table-des">
				<div class="table-responsive">
					@include('frontend.messages')
					<table class="table table-hover">
						<tbody>
							<tr>
								<th scope="row">Amount({{config('constants.currency')['symbol']}})</th>
								<td>{{number_format($withdraw->amount,4)}}</td>
							</tr>
							<tr>
								<th scope="row">Wallet Address</th>
								<td>{{ $withdraw->wallet_address }}</td>
							</tr>
							</tr>
							<tr> 
								<th scope="row">Created At</th>
								<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($withdraw->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
							</tr>
							<tr> 
								<th scope="row">Approved At</th>
								<td>@if(!empty($withdraw->approved_at))
								{{ \Carbon\Carbon::createFromTimeStamp(strtotime($withdraw->approved_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}
								@endif</td>
							</tr>
							<tr> 
								<th scope="row">Status</th>
								<td> 
									@if($withdraw->status == 0)
										<span class="badge bg-warning">Pending</span>
									@elseif($withdraw->status == 1)
										<span class="badge bg-success">Approved</span>
									@elseif($withdraw->status == 2)
										<span class="badge bg-danger">Rejected</span>
									@endif
								</td>
							</tr>
							@if(!empty($withdraw->reason))
							<tr> 
								<th scope="row">Rejection Reason</th>
								<td>{{$withdraw->reason}}</td>
							</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
