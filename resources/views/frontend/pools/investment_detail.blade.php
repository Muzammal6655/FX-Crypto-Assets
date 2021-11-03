@extends('frontend.layouts.app')
@section('title', 'Pool Investment View')
@section('content')
<div class="main-wrapper">
	<div class="container">
	@if($model->status == 0)
		<div class="card mb-4">
			<div class="card-header">
				Pool Investment Transfer
			</div>
			<div class="card-body">
				<form class="form-inline pool-transfer-form"
					  action="{{url('pool-investments/'.Hashids::encode($model->id).'/transfer')}}" method="post">
				@csrf
			    <div class="form-group mr-3">
			     	<select class="form-control"  name="pool_id" required="required">
			            <option value="">Select Pool</option>
			            @foreach ($pools as $pool)
			            <option value="{{$pool->id}}">{{$pool->name}}</option>
			            @endforeach 
			        </select>
			    </div>   
			    <button type="submit" class="btn btn-primary btn-fullrounded btn-apply">
			          <span>Transfer</span>
			    </button>
	  			</form>
			</div>
		</div>
 @endif
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
							<th scope="row">Customer</th>
							<td>{{ $model->User->name }}</td>
						</tr>
						<tr>
							<th scope="row">Amount ({{config('constants.currency')['symbol']}})</th>
							<td>{{number_format( $model->deposit_amount,4)}}</td>
						</tr>
						<tr>
							<th scope="row">Profit (%)</th>
							<td>{{ $model->profit_percentage }}</td>
						</tr>
						<tr>
							<th scope="row">Profit ({{config('constants.currency')['symbol']}})</th>
							<td>@if(!empty($model->profit))
								{{number_format($model->profit,4)}} 
								@endif
							</td>
						</tr>
						<tr> 
							<th scope="row">Management Fee (%)</th>
							<td>@if(!empty($model->management_fee_percentage))
								{{number_format($model->management_fee_percentage,4)}} 
								@endif
							</td>
 						</tr>
						<tr> 
							<th scope="row">Management Fee  ({{config('constants.currency')['symbol']}})</th>
 							<td style="color:red;">@if(!empty($model->management_fee))
								-{{number_format($model->management_fee,4)}} 
								@endif
							</td>
 						</tr>
						<tr> 
							<th scope="row">Commission ({{config('constants.currency')['symbol']}})</th>
							<td style="color:red;">@if(!empty($model->commission))
								-{{number_format($model->commission,4)}} 
								@endif
							</td>
 						</tr>
						<tr> 
							<th scope="row">Started Date</th>
							<td>
								@if(!empty($model->start_date))
								{{ \Carbon\Carbon::createFromTimeStamp($model->start_date)->tz(auth()->user()->timezone)->format('d M, Y') }}
								@endif
							</td>
						</tr>
						<tr> 
							<th scope="row">Ended Date</th>
							<td>
								@if(!empty($model->end_date))
								{{ \Carbon\Carbon::createFromTimeStamp($model->end_date)->tz(auth()->user()->timezone)->format('d M, Y') }}
								@endif
							</td>
						</tr>
						<tr> 
							<th scope="row">Approved At</th>
							<td>
								@if(!empty($model->approved_at))
								{{ \Carbon\Carbon::createFromTimeStamp(strtotime($model->approved_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y') }}
								@endif
							</td>
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
</div>

@endsection
