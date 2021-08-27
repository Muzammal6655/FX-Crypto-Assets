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
						<th scope="col">Approved At</th>
						<th scope="col">Status</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					@php $count = $deposits->firstItem();  @endphp
					@forelse($deposits as $deposit)
						<tr>
							<th scope="row">{{ $count++ }}</th>
							<td>{{ !empty($deposit->pool_id) ? $deposit->pool->name : '' }}</td>
							<td>{{ $deposit->amount }}</td>
							<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($deposit->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
							<td>{{ !empty($deposit->approved_at) ? \Carbon\Carbon::createFromTimeStamp(strtotime($deposit->approved_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A')  : '' }}</td>
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
								@if(!$deposit->status == 2)
           						<a href="{{ url('/deposits/' . Hashids::encode($deposit->id) .'/edit') }}" class="btn  btn-success"><i class="fa fa-edit"></i></a>
								@endif
								<a href="{{ url('/deposits/' . Hashids::encode($deposit->id)) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
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
	    	{{ $deposits->links() }}
	  	</div>
	</div>
</div>
@endsection
