@extends('frontend.layouts.app')
@section('title', 'Pool Investment View')
@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Pool Investment View
		</div>
		<div class="card-body">
			@include('frontend.messages')
			<table class="table table-hover">
				<tbody>
					@if(!empty($model->pool_id))
					<tr>
						<th scope="row">Pool</th>
					 	<td>{{ $model->pool->name }}</td>
					</tr>
					@endif
					<tr>
						<th scope="row">Invester</th>
					 	<td>{{ $model->User->name }}</td>
					</tr>
	 				<tr>
						<th scope="row">Amount ({{config('constants.currency')['symbol']}})</th>
					 	<td>{{ $model->deposit_amount }}</td>
					</tr>
					<tr>
						<th scope="row">Profit (%)</th>
					 	<td>{{ $model->profit_percentage }}</td>
					</tr>
					<tr>
						<th scope="row">Profit ({{config('constants.currency')['symbol']}})</th>
					 	<td>{{ $model->profit }}</td>
					</tr>
					<tr> 
						<th scope="row">Management Fee (%)</th>
					 	<td>{{ $model->management_fee_percentage }}</td>
					</tr>
					<tr> 
						<th scope="row">Management Fee  ({{config('constants.currency')['symbol']}})</th>
					 	<td>{{ $model->management_fee }}</td>
					</tr>
					<tr> 
						<th scope="row">Commission ({{config('constants.currency')['symbol']}})</th>
					 	<td>{{ $model->commission }}</td>
					</tr>
					<tr> 
						<th scope="row">Started Date</th>
					 	<td>{{ \Carbon\Carbon::createFromTimeStamp($model->start_date)->tz(auth()->user()->timezone)->format('d M, Y') }}</td>
					</tr>
					<tr> 
						<th scope="row">Ended Date</th>
					 	<td>{{ \Carbon\Carbon::createFromTimeStamp($model->end_date)->tz(auth()->user()->timezone)->format('d M, Y') }}</td>
					</tr>
					<tr> 
						<th scope="row">Status</th>
					 	<td> 
				 			@if($model->status == 0)
								<span class="badge bg-warning">Pending</span>
							@elseif($model->status == 1)
								<span class="badge bg-success">Approved</span>
							@elseif($model->status == 2)
								<span class="badge bg-danger">Rejected</span>
							@endif
						</td>
					</tr>
					@if($model->status == 2 && !empty($model->reason))
					<tr> 
						<th scope="row">Rejection Reason</th>
						<td>{{$model->reason}}</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
