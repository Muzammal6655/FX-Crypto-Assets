@extends('frontend.layouts.app')
@section('title', 'Transactions')
@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Transactions History
		</div>
		<div class="card-body">
			@include('frontend.messages')
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Type</th>
						<th scope="col">Amount ({{config('constants.currency')['symbol']}})</th>
						<th scope="col">Actual Amount ({{config('constants.currency')['symbol']}})</th>
						<th scope="col">Fee (%)</th>
						<th scope="col">Fee ({{config('constants.currency')['symbol']}})</th>
						<th scope="col">Commission ({{config('constants.currency')['symbol']}})</th>
						<th scope="col">Created At</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					@php $count = $transactions->firstItem();  @endphp
					@forelse($transactions as $transaction)
						<tr>
							<th scope="row">{{ $count++ }}</th>
							<td>{{ $transaction->type }}</td>
							<td>{{ $transaction->amount }}</td>
							<td>{{ $transaction->actual_amount }}</td>
							<td>{{ $transaction->fee_percentage }}</td>
							<td>{{ $transaction->fee_amount }}</td>
							<td>{{$transaction->commission}}</td>
							<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($transaction->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
							<td>
								<a href="{{ url('/transactions/' . Hashids::encode($transaction->id)) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
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
	    	{{ $transactions->links() }}
	  	</div>
	</div>
</div>
@endsection
