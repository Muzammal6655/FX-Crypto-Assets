@extends('frontend.layouts.app')
@section('title', 'Transactions View')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="card-header">
				Transactions View
			</div>
			<div class="card-body">
				@include('frontend.messages')
				<table class="table table-hover">
					<tbody>
						<tr>
							<th scope="row">Invester</th>
							<td>{{ $transaction->User->name }}</td>
						</tr>
						<tr>
							<th scope="row">Type</th>
							<td>{{ $transaction->type }}</td>
						</tr>
						<tr>
							<th scope="row">Amount ({{config('constants.currency')['symbol']}})</th>
							<td>{{ $transaction->amount }}</td>
						</tr>
						<tr>
							<th scope="row">Actual Amount ({{config('constants.currency')['symbol']}})</th>
							<td>{{ $transaction->actual_amount }}</td>
						</tr>
						<tr>
							<th scope="row">Description</th>
							<td>{{ $transaction->description }}</td>
						</tr>
						<tr>
							<th scope="row">Management Fee (%)</th>
							<td>{{ $transaction->fee_percentage }}</td>
						</tr>
						<tr>
							<th scope="row">Management Fee ({{config('constants.currency')['symbol']}})</th>
							<td>{{ $transaction->fee_amount }}</td>
						</tr>
						<tr>
							<th scope="row">Commission ({{config('constants.currency')['symbol']}})</th>
							<td>{{ $transaction->commission }}</td>
						</tr>
						<tr> 
							<th scope="row">Started Date</th>
							<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($transaction->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
