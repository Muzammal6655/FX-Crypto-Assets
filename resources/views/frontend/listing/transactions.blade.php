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
						<th scope="col">Fee Amount ({{config('constants.currency')['symbol']}})</th>
						<th scope="col">Fee Percentage (%)</th>
						<th scope="col">Description</th>
						<th scope="col">Created At</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					@php $count = $transactions->firstItem();  @endphp
					@forelse($transactions as $transaction)
						<tr>
							<th scope="row">{{ $count++ }}</th>
							<th>{{ $transaction->type }}</th>
							<th>{{ $transaction->amount }}</th>
							<th>{{ $transaction->actual_amount }}</th>
							<th>{{ $transaction->fee_amount }}</th>
							<th>{{ $transaction->fee_percentage }}</th>
							<th title="{{$transaction->description}}"><i class="fa fa-info-circle"></i></th>
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
