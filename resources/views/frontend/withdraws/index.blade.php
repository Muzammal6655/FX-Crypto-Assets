@extends('frontend.layouts.app')
@section('title', 'Withdraws')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="mbl-card-header card-header d-flex align-items-center justify-content-between flex-lg-nowrap flex-md-nowrap flex-wrap">
				Withdraws History
				<span class="pull-right">
					<a href="{{url('/withdraws/create')}}" class="btn btn-success mt-lg-0 mt-md-0 mt-sm-0 mt-2">Create Withdraw</a>
				</span>
			</div>
			<div class="card-body general-table-des">
				<div class="table-responsive">
					@include('frontend.messages')
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Amount ({{config('constants.currency')['symbol']}})</th>
								<th scope="col">Wallet Address</th>
								<th scope="col">Created At</th>
								<th scope="col">Approved At</th>
								<th scope="col">Status</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							@php $count = $withdraws->firstItem();  @endphp
							@forelse($withdraws as $withdraw)
								<tr>
									<th scope="row">{{ $count++ }}</th>
									<td>{{number_format($withdraw->amount,4)}}</td>
									<td>{{ $withdraw->wallet_address }}</td>
									<td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($withdraw->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
									<td>{{ !empty($withdraw->approved_at) ? \Carbon\Carbon::createFromTimeStamp(strtotime($withdraw->approved_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A')  : '' }}</td>
									<td>
										@if($withdraw->status == 0)
											<span class="badge bg-warning">Pending</span>
										@elseif($withdraw->status == 1)
											<span class="badge bg-success">Approved</span>
										@elseif ($withdraw->status == 2)
											<span class="badge bg-danger">Rejected</span>
										@endif
									</td>
									<td>
										<a href="{{ url('/withdraws/' . Hashids::encode($withdraw->id)) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
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
			</div>
			<div class="card-footer text-center">
				{{ $withdraws->links() }}
			</div>
		</div>
	</div>
</div>
@endsection