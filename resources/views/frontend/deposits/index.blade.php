@extends('frontend.layouts.app')
@section('title', 'Deposits')
@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Deposits History
			<span class="pull-right">
				<a href="{{url('/deposits/create')}}" class="btn btn-success">Create Deposit</a>
			</span>
		</div>
		<div class="card-body">
			@include('frontend.messages')
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Pool</th>
						<th scope="col">Amount ({{config('constants.currency')['symbol']}})</th>
						<th scope="col">Created At</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($deposits as $deposit)
						<tr>
							<th scope="row">1</th>
							<td>{{ !empty($deposit->pool_id) ? $deposit->pool->name : '' }}</td>
							<td>{{ $deposit->amount }}</td>
							<td>{{ $deposit->created_at }}</td>
							<td>
								@if($deposit->status == 0)
									<span class="badge bg-warning">Pending</span>
				                @elseif($deposit->status == 1)
				                    <span class="badge bg-success">Approved</span>
				                @elseif ($deposit->status == 2)
				                    <span class="badge bg-danger">Rejected</span>
				                @endif
							</td>
							<td>
								<a href="{{ url('/deposits/' . Hashids::encode($deposit->id)) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
