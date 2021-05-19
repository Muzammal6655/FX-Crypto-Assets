@extends('admin.layouts.app')

@section('title', 'Deposits')
@section('sub-title', $action.' Deposit')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/deposits')}}"><i class="fa fa-money"></i>Deposits</a></li>
			<li>View</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">View Deposit</h3>
					</div>
					<div class="panel-body">
						@include('admin.messages')
						<form id="deposits-form" class="form-horizontal label-left" action="{{url('admin/deposits')}}" enctype="multipart/form-data" method="POST">
							@csrf

							<input name="id" type="hidden" value="{{ $model->id }}" />

							<div class="form-group">
								<label for="user" class="col-sm-3 control-label">User</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->user->name }}">
								</div>
							</div>

							@if(!empty($model->pool_id))
								<div class="form-group">
									<label for="pool" class="col-sm-3 control-label">Pool</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" readonly="" value="{{ $model->pool->name }}">
									</div>
								</div>
							@endif

							<div class="form-group">
								<label for="amount" class="col-sm-3 control-label">Amount ({{config('constants.currency')['symbol']}})</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->amount }}">
								</div>
							</div>

							<div class="form-group">
								<label for="wallet_address" class="col-sm-3 control-label">Wallet Address</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->wallet_address }}">
								</div>
							</div>

							<div class="form-group">
								<label for="transaction_id" class="col-sm-3 control-label">Transaction Id</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->transaction_id }}">
								</div>
							</div>
 
							<div class="form-group">
								<label for="proof" class="col-sm-3 control-label">Proof</label>
								<div class="col-sm-9">
									@if (!empty($model->proof) && \File::exists(public_path() . '/storage/deposits/' . $model->user_id . '/deposits/' . $model->proof))
										<a href="{{ checkImage(asset('storage/deposits/' . $model->user_id . '/deposits/' . $model->proof),'placeholder.png',$model->proof) }}" download="">Download</a>
									@else
										<strong><i>No proof provided</i></strong>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									@if($model->status == 0)
										<span class="label label-warning">Pending</span>
									@elseif($model->status == 1)
										<span class="label label-success">Approved</span>
									@elseif($model->status == 2)
										<span class="label label-danger">Rejected</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label for="reason" class="col-sm-3 control-label">Reason</label>
								<div class="col-sm-9">
									<textarea name="reason" maxlength="1000" class="form-control" rows="5">{{ $model->reason}}</textarea>
								</div>
							</div>

							<div class="text-right">
								<a href="{{url('admin/deposits')}}">
									<button type="button" class="btn cancel btn-fullrounded">
										<span>Cancel</span>
									</button>
								</a>

								<button type="submit" class="btn btn-primary btn-fullrounded">
									<span>Save</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	$(function(){
        $('#deposits-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: true,
            
            highlight: function (e) {
            	$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
	            $(e).closest('.form-group').removeClass('has-error');
	            $(e).remove();
            },
            errorPlacement: function (error, element) {
	            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
		            var controls = element.closest('div[class*="col-"]');
		            if (controls.find(':checkbox,:radio').length > 1)
		                    controls.append(error);
		            else
	                    error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
	            } 
	            else if (element.is('.select2')) {
	            	error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
	            } 
	            else if (element.is('.chosen-select')) {
	            	error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
	            } 
	            else
                    error.insertAfter(element);
            },
            invalidHandler: function (form,validator) {
            	$('html, body').animate({
		            scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
		        }, 500);
            },
            submitHandler: function (form,validator) {
            	if($(validator.errorList).length == 0)
            	{
            		document.getElementById("page-overlay").style.display = "block";
            		return true;
            	}
            }
        });
    });

</script>
@endsection