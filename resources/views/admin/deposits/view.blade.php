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
							<input name="status" type="hidden" value="2" />

							<div class="form-group">
								<label for="user" class="col-sm-3 control-label">Investor</label>
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
									<input type="text" class="form-control" readonly="" value="{{number_format($model->amount,4)}}">
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
									@if (!empty($model->proof) && \File::exists(public_path() . '/storage/users/' . $model->user_id . '/deposits/' . $model->proof))
										<a href="{{ checkImage(asset(env('PUBLIC_URL').'storage/users/' . $model->user_id . '/deposits/' . $model->proof),'placeholder.png',$model->proof) }}" download="">Download</a>
									@else
										<strong><i>No proof provided</i></strong>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label for="created_at" class="col-sm-3 control-label">Created At</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ \Carbon\Carbon::createFromTimeStamp(strtotime($model->created_at), "UTC")->tz(session('timezone'))->format('d M, Y h:i:s A') }}">
								</div>
							</div>

							<div class="form-group">
								<label for="approved_at" class="col-sm-3 control-label">Approved At</label>
								@if($model->approved_at != '')
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ \Carbon\Carbon::createFromTimeStamp(strtotime($model->approved_at), "UTC")->tz(session('timezone'))->format('d M, Y h:i:s A') }}">
								</div>
								@endif
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

							@if($model->status == 0 || $model->status == 2)
								<h4 class="heading">Rejection Reason</h4>

								<div class="form-group">
									<label for="role" class="col-sm-3 control-label">Select Reason</label>
									<div class="col-sm-9">
										<select class="form-control" name="reason_select">
											<option value="">Select Reason</option>
											<option value="Deposit does not match Receipt please contact IFX – admin@interestingfx.com">Deposit does not match Receipt</option>
											<option value="Wallet Address has been changed from Companies account please contact IFX - admin@interestingfx.com">Wallet Address has been changed from Companies account</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="reason" class="col-sm-3 control-label">Other</label>
									<div class="col-sm-9">
										<textarea name="reason" maxlength="1000" class="form-control" rows="3">{{ $model->reason}}</textarea>
									</div>
								</div>
							@endif

							<div class="text-right">
								@if($model->status == 0 || $model->status == 2)
									<a href="{{url('admin/deposits')}}">
										<button type="button" class="btn cancel btn-fullrounded">
											<span>Cancel</span>
										</button>
									</a>

									@if(have_right('deposits-approve'))
										<a href="{{url('admin/deposits/'. Hashids::encode($model->id) . '/approve')}}">
											<button type="button" class="btn btn-success btn-fullrounded" onclick="myButtonClicked(this)">
												<span>Approve</span>
											</button>
										</a>
									@endif

									@if(have_right('deposits-reject'))
										<button type="submit" class="btn btn-danger btn-fullrounded">
											<span>Reject</span>
										</button>
									@endif
								@endif
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

    function myButtonClicked(el)
	{
    el.disabled = true; 
	}

</script>
@endsection