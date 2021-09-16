@extends('frontend.layouts.app')
@section('title', 'Transactions')
@section('content')
<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="mbl-card-header card-header d-flex align-items-center justify-content-between flex-lg-nowrap flex-md-nowrap flex-wrap">
				Transactions History
				<span class="pull-right">
					<a href="{{url('/current-month-statements')}}" class="btn btn-success mt-lg-0 mt-md-0 mt-sm-0 mt-2">Download Montly statement</a>
				</span>
			</div>
			<div class="card-body general-table-des">
				<div class="table-responsive">
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
									<td>{{ ucwords($transaction->type) }}</td>
									<td>{{number_format($transaction->amount,4) }}</td>
									<td>{{number_format($transaction->actual_amount,4)}}</td>
									@if(!empty($transaction->fee_percentage))
									<td>{{ number_format($transaction->fee_percentage,4) }}</td>
									@else
									<td></td>
									@endif
 									@if(!empty($transaction->fee_amount))
									<td>{{number_format($transaction->fee_amount,4)}}</td>
									@else
									<td></td>
									@endif
									@if(!empty($transaction->commission))
									<td>{{number_format($transaction->commission,4)}}</td>
									@else
									<td></td>
									@endif
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
			</div>
			<div class="card-footer text-center">
				{{ $transactions->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
