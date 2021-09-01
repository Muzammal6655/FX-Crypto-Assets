@extends('frontend.layouts.app')
@section('title', 'View Deposits')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between">
				Deposits View
				<span class="pull-right">
					<a href="{{url('/deposits/create')}}" class="btn btn-success">Create Deposit</a>
				</span>
			</div>
			<div class="card-body">
				@include('frontend.messages')
				<table class="table table-hover">
					<tbody>
						@if(!empty($deposit->pool_id))
						<tr>
							<th scope="row">Pool</th>
							<td>{{ $deposit->pool->name }}</td>
						</tr>
						@endif
						<tr>
							<th scope="row">Amount({{config('constants.currency')['symbol']}})</th>
							<td>{{ $deposit->amount }}</td>
						</tr>
						<tr>
							<th scope="row">Wallet Address</th>
							<td>{{ $deposit->wallet_address }}</td>
						</tr>
						<tr>
							<th scope="row">Transaction Id</th>
							<td>{{ $deposit->transaction_id }}</td>
						</tr>
						<tr>
							<th scope="row">Proof</th>
							<td>

								@if (!empty($deposit->proof) && \File::exists(public_path() . '/storage/users/' . $deposit->user_id . '/deposits/' . $deposit->proof))
									<a class="btn btn-secondary" href="{{ checkImage(asset('storage/users/' . $deposit->user_id . '/deposits/' . $deposit->proof),'placeholder.png',$deposit->proof) }}" download="">Download</a>
								@else
									<strong><i>No proof provided</i></strong>
								@endif
							</td>
						</tr>
						<tr> 
							<th scope="row">Created At</th>
							<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($deposit->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
						</tr>
						
						<tr> 
							<th scope="row">Approved At</th>
							@if(!empty($deposit->approved_at))
							<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($deposit->approved_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
							@endif
						</tr>
						
						<tr> 
							<th scope="row">Status</th>
							<td> 
								@if($deposit->status == 0)
									<span class="badge bg-warning">Pending</span>
								@elseif($deposit->status == 1)
									<span class="badge bg-success">Approved</span>
								@elseif($deposit->status == 2)
									<span class="badge bg-danger">Rejected</span>
								@endif
							</td>
						</tr>
						@if( $deposit->status == 2 && !empty($deposit->reason))
						<tr> 
							<th scope="row">Rejection Reason</th>
							<td>{{$deposit->reason}}</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
