@extends('admin.layouts.app')

@section('title', 'Investors')
@section('sub-title', 'Transaction Detail')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/investors')}}"><i class="fa fa-money"></i>investors</a></li>
			<li><a href="{{url("admin/investors/" . Hashids::encode($model->user->id).'/transactions')}}"><i class="fa fa-exchange"></i>Transactions</a></li>
			<li>Detail</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Transaction Detail</h3>
					</div>
					<div class="panel-body">
						@include('admin.messages')
						<div class="form-horizontal label-left" >
							
							<div class="form-group">
								<label for="user" class="col-sm-3 control-label">Investor</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->user->name }}">
								</div>
							</div>

							<div class="form-group">
								<label for="user" class="col-sm-3 control-label">Type</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->type }}">
								</div>
							</div>

							<div class="form-group">
								<label for="amount" class="col-sm-3 control-label">Amount ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->amount }}">
								</div>
							</div>

							<div class="form-group">
								<label for="amount" class="col-sm-3 control-label">Actual Amount ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->actual_amount }}">
								</div>
							</div>

							<div class="form-group">
								<label for="transaction_id" class="col-sm-3 control-label">Description </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->description }}">
								</div>
							</div>

							<div class="form-group">
								<label for="transaction_id" class="col-sm-3 control-label">Fee Amount  ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->fee_amount }}">
								</div>
							</div>

							<div class="form-group">
								<label for="transaction_id" class="col-sm-3 control-label">Management Fee Percentage(%)</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->fee_percentage }}">
								</div>
							</div>

							<div class="form-group">
								<label for="created_at" class="col-sm-3 control-label">Created At</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->created_at }}">
								</div>
							</div>

							<div class="text-right">						 
								<a href="{{url("admin/investors/" . Hashids::encode($model->user->id).'/transactions')}}")}}">
									<button type="button" class="btn cancel btn-fullrounded">
										<span>Back</span>
									</button>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection
