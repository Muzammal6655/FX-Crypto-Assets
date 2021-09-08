@extends('admin.layouts.app')
@section('title', 'Withdraws')
@section('sub-title', $action.' Withdraw')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/withdraws')}}"><i class="fa fa-money"></i>Withdraws</a></li>
			<li>View</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">View Withdraw</h3>
					</div>
					<div class="panel-body">
						@include('admin.messages')
						<form id="withdraws-form" class="form-horizontal label-left" action="{{url('admin/withdraws')}}" enctype="multipart/form-data" method="POST">
							@csrf

							<input name="id" type="hidden" value="{{ $model->id }}" />
							<input name="status" type="hidden" value="2" />

							<div class="form-group">
								<label for="user" class="col-sm-3 control-label">Investor</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $model->user->name }}">
								</div>
							</div>

							@if($model->status != 1)
	                            <div class="form-group">
									<label for="user" class="col-sm-3 control-label">Account Balance</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" readonly="" value="{{ $model->user->account_balance}}">
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
								<label for="created_at" class="col-sm-3 control-label">Created At</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ \Carbon\Carbon::createFromTimeStamp(strtotime($model->created_at), "UTC")->tz(session('timezone'))->format('d M, Y h:i:s A') }}">
								</div>
							</div>

							<div class="form-group">
								<label for="approved_at" class="col-sm-3 control-label">Approved At</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ \Carbon\Carbon::createFromTimeStamp(strtotime($model->approved_at), "UTC")->tz(session('timezone'))->format('d M, Y h:i:s A') }}">
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

							@if($model->status == 0 || $model->status == 2)
								<div class="form-group">
									<label for="reason" class="col-sm-3 control-label">Rejection Reason</label>
									<div class="col-sm-9">
										<textarea name="reason" maxlength="1000" class="form-control" rows="3">{{ $model->reason}}</textarea>
									</div>
								</div>
							@endif
                            
							<div class="text-right">
								@if($model->status == 0 || $model->status == 2)
									<a href="{{url('admin/withdraws')}}">
										<button type="button" class="btn cancel btn-fullrounded">
											<span>Cancel</span>
										</button>
									</a>

									@if(have_right('withdraws-approve'))
										<a href="{{url('admin/withdraws/'. Hashids::encode($model->id) . '/approve')}}">
											<button type="button" class="btn btn-success btn-fullrounded">
												<span>Approve</span>
											</button>
										</a>
									@endif

									@if(have_right('withdraws-reject'))
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
        $('#withdraws-form').validate({
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