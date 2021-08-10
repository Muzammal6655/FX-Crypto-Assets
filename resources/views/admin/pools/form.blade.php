@extends('admin.layouts.app')
@section('title', 'Pools')
@section('sub-title', $action.' Pool')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/pools')}}"><i class="fa fa-product-hunt"></i>Pools</a></li>
			<li>{{$action}}</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">{{$action}} Pool</h3>
					</div>
					<div class="panel-body">
						@include('admin.messages')
						<form id="pools-form" class="form-horizontal label-left" action="{{url('admin/pools')}}"
							enctype="multipart/form-data" method="POST">
							@csrf

							<input type="hidden" name="action" value="{{$action}}" />
							<input name="id" type="hidden" value="{{ $model->id }}" />

							<div class="form-group">
								<label for="name" class="col-sm-3 control-label">Name*</label>
								<div class="col-sm-9">
									<input type="text" name="name" maxlength="30" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('name') : $model->name}}">
								</div>
							</div>

							<div class="form-group">
								<label for="description" class="col-sm-3 control-label">Description</label>
								<div class="col-sm-9">
									<textarea name="description" maxlength="1000" class="form-control" rows="5">{{ ($action == 'Add') ? old('description') : $model->description}}</textarea>
								</div>
							</div>

							<div class="form-group">
								<label for="wallet_address" class="col-sm-3 control-label">Wallet Address*</label>
								<div class="col-sm-9">
									<input type="text" name="wallet_address" maxlength="100" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('wallet_address') : $model->wallet_address}}">
								</div>
							</div>

							<div class="form-group">
								<label for="min_deposits" class="col-sm-3 control-label">Min Deposits ({{config('constants.currency')['symbol']}})*</label>
								<div class="col-sm-9">
									<input type="number" name="min_deposits" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('min_deposits') : $model->min_deposits}}">
								</div>
							</div>

							<div class="form-group">
								<label for="max_deposits" class="col-sm-3 control-label">Max Deposits ({{config('constants.currency')['symbol']}})*</label>
								<div class="col-sm-9">
									<input type="number" name="max_deposits" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('max_deposits') : $model->max_deposits}}">
								</div>
							</div>

							<div class="form-group">
								<label for="users_limit" class="col-sm-3 control-label">Users Limit*</label>
								<div class="col-sm-9">
									<input type="number" name="users_limit" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('users_limit') : $model->users_limit}}">
								</div>
							</div>

							<div class="form-group">
								<label for="profit_percentage" class="col-sm-3 control-label">Profit (%)*</label>
								<div class="col-sm-9">
									<input type="number" name="profit_percentage" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('profit_percentage') : $model->profit_percentage}}">
								</div>
							</div>

							<div class="form-group">
								<label for="management_fee_percentage" class="col-sm-3 control-label">Management Fee (%)*</label>
								<div class="col-sm-9">
									<input type="number" name="management_fee_percentage" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('management_fee_percentage') : $model->management_fee_percentage}}">
								</div>
							</div>

							<div class="form-group">
								<label for="start_date" class="col-sm-3 control-label">Start Date*</label>
								<div class="col-sm-9">
									<input type="date" name="start_date" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('start_date') : $model->start_date}}">
								</div>
							</div>

							<div class="form-group">
								<label for="end_date" class="col-sm-3 control-label">End Date*</label>
								<div class="col-sm-9">
									<input type="date" name="end_date" class="form-control" required=""
										value="{{ ($action == 'Add') ? old('end_date') : $model->end_date}}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									@php $status = ($action == 'Add') ? old('status') : $model->status @endphp
									<label class="fancy-radio">
										<input name="status" value="1" type="radio" {{ ($status == 1) ? 'checked' : '' }}>
										<span><i></i>Active</span>
									</label>
									<label class="fancy-radio">
										<input name="status" value="0" type="radio" {{ ($status == 0) ? 'checked' : '' }}>
										<span><i></i>Disable</span>
									</label>
								</div>
							</div>

							<div class="text-right">
								<a href="{{url('admin/pools')}}">
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
        $('#pools-form').validate({
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