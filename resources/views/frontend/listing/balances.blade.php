 @extends('frontend.layouts.app')
@section('title', 'Balances')
@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Balances History
			<span class="pull-right">
				 <strong> Account Balance({{config('constants.currency')['symbol']}}):
				 	{{$user->account_balance}}</strong>
			</span>
		</div>
		<div class="card-body">
			@include('frontend.messages')
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Type</th>
						<th scope="col">Amount ({{config('constants.currency')['symbol']}})</th>
						<th scope="col">Created At</th>
 					</tr>
				</thead>
				<tbody>
					@php $count = $balances->firstItem();  @endphp
					@forelse($balances as $balance)
						<tr>
							<th scope="row">{{ $count++ }}</th>
							<th>{{ $balance->type }}</th>
							@if( $balance->amount <= 0)
								<th style="color:red;">{{ $balance->amount }}</th>
							@else
								<th style="color:green;">+{{ $balance->amount }}</th>
							@endif
							<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($balance->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
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
	    	{{ $balances->links() }}
	  	</div>
	</div>
</div>
@endsection
