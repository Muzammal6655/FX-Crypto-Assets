 @extends('frontend.layouts.app')
@section('title', 'Balances')
@section('content')

<div class="main-wrapper">
	<div class="container">
		<div class="card">
			<div class="mbl-card-header card-header d-flex align-items-center justify-content-between flex-lg-nowrap flex-md-nowrap flex-wrap">
				Balances History
				<span class="pull-right">
					<strong> Account Balance({{config('constants.currency')['symbol']}}):
						{{number_format($user->account_balance,2)}}</strong>
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
								<th scope="col">Created At</th>
							</tr>
						</thead>
						<tbody>
							@php $count = $balances->firstItem();  @endphp
							@forelse($balances as $balance)
								<tr>
									<th scope="row">{{ $count++ }}</th>
									<td>{{ ucwords($balance->type) }}</td>
									@if( $balance->amount <= 0)
										<td style="color:red;">{{number_format($balance->amount,2)}}</td>
									@else
										<td style="color:green;">+{{number_format($balance->amount,2)}}</td>
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
			</div>
			<div class="card-footer text-center">
				{{ $balances->links() }}
			</div>
		</div>
	</div>
</div>
@endsection
