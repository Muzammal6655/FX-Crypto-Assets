@extends('admin.layouts.app')

@section('title', 'Investors')
@section('sub-title', 'Investor Documents')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/investors')}}"><i class="fa fa-user"></i>Investors</a></li>
			<li>Documents Verification</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Documents Verification</h3>
					</div>
					<div class="panel-body">
						@include('admin.messages')
						<form id="investors-form" class="form-horizontal label-left" action="{{url('admin/investors/verify-documents')}}" enctype="multipart/form-data" method="POST">
							@csrf

							<input name="id" type="hidden" value="{{ $user->id }}" />
 
							<div class="form-group">
								<label for="photo" class="col-sm-3 control-label">Photo</label>
								<div class="col-sm-9">
									@if (!empty($user->photo) && \File::exists(public_path() . '/storage/users/' . $user->id . '/documents/' . $user->photo))
										<a href="{{ checkImage(asset('storage/users/' . $user->id . '/documents/' . $user->photo),'placeholder.png',$user->photo) }}" download="">Download</a>
									@else
										<strong><i>No photo provided</i></strong>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Photo Status</label>
								<div class="col-sm-9">
									@php $photo_status = $user->photo_status @endphp
									<label class="fancy-radio">
										<input name="photo_status" value="0" type="radio" {{ ($photo_status == 0) ? 'checked' : '' }}>
										<span><i></i>Pending</span>
									</label>
									<label class="fancy-radio">
										<input name="photo_status" value="1" type="radio" {{ ($photo_status == 1) ? 'checked' : '' }}>
										<span><i></i>Approved</span>
									</label>
									<label class="fancy-radio">
										<input name="photo_status" value="2" type="radio" {{ ($photo_status == 2) ? 'checked' : '' }}>
										<span><i></i>Rejected</span>
									</label>
								</div>
							</div>

							<div class="form-group">
								<label for="passport" class="col-sm-3 control-label">Passport</label>
								<div class="col-sm-9">
									@if (!empty($user->passport) && \File::exists(public_path() . '/storage/users/' . $user->id . '/documents/' . $user->passport))
										<a href="{{ checkImage(asset('storage/users/' . $user->id . '/documents/' . $user->passport),'placeholder.png',$user->passport) }}" download="">Download</a>
									@else
										<strong><i>No passport provided</i></strong>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Passport Status</label>
								<div class="col-sm-9">
									@php $passport_status = $user->passport_status @endphp
									<label class="fancy-radio">
										<input name="passport_status" value="0" type="radio" {{ ($passport_status == 0) ? 'checked' : '' }}>
										<span><i></i>Pending</span>
									</label>
									<label class="fancy-radio">
										<input name="passport_status" value="1" type="radio" {{ ($passport_status == 1) ? 'checked' : '' }}>
										<span><i></i>Approved</span>
									</label>
									<label class="fancy-radio">
										<input name="passport_status" value="2" type="radio" {{ ($passport_status == 2) ? 'checked' : '' }}>
										<span><i></i>Rejected</span>
									</label>
								</div>
							</div>

							<div class="form-group">
								<label for="au_doc_verification" class="col-sm-3 control-label">AU Doc Verification Service</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->au_doc_verification }}">
								</div>
							</div>

							<div class="form-group">
								<label for="documents_rejection_reason" class="col-sm-3 control-label">Documents Rejection Reason</label>
								<div class="col-sm-9">
									<textarea name="documents_rejection_reason" maxlength="1000" class="form-control" rows="5">{{ $user->documents_rejection_reason}}</textarea>
								</div>
							</div>

							<div class="text-right">
								<a href="{{url('admin/investors')}}">
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
        $('#documents-form').validate({
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