@extends('frontend.layouts.app')
@section('title', 'View Deposits')
@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
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
					 	<th scope="row">{{ $deposit->pool->name }}</th>
					</tr>
					@endif
	 				<tr>
						<th scope="row">Amount({{config('constants.currency')['symbol']}})</th>
					 	<th scope="row">{{ $deposit->amount }}</th>
					</tr>
					<tr>
						<th scope="row">Wallet Address</th>
					 	<th scope="row">{{ $deposit->wallet_address }}</th>
					</tr>
					<tr>
						<th scope="row">Transaction Id</th>
					 	<th scope="row">{{ $deposit->transaction_id }}</th>
					</tr>
					<tr>
						<th scope="row">Proof</th>
					 	<th scope="row">

					 		@if (!empty($deposit->proof) && \File::exists(public_path() . '/storage/users/' . $deposit->user_id . '/deposits/' . $deposit->proof))
									<a href="{{ checkImage(asset('storage/users/' . $deposit->user_id . '/deposits/' . $deposit->proof),'placeholder.png',$deposit->proof) }}" download="">Download</a>
							@else
								<strong><i>No proof provided</i></strong>
							@endif
						</th>
					</tr>
					<tr> 
						<th scope="row">Created At</th>
					 	<th scope="row">{{date('d M,Y', strtotime($deposit->created_at))}}</th>
					</tr>
					<tr> 
						<th scope="row">Status</th>
					 	<th scope="row"> 
					 			@if($deposit->status == 0)
									<span class="badge bg-danger">Pending</span>
								@elseif($deposit->status == 1)
									<span class="badge bg-danger">Approved</span>
								@elseif($deposit->status == 2)
									<span class="badge bg-danger">Rejected</span>
								@endif
						</th>
					</tr>
					@if(!empty($deposit->reason))
					<tr> 
						<th scope="row">Rejection Reason</th>
						<th scope="row">{{$deposit->reason}}</th>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
