@extends('admin.layouts.app')
@section('title', 'Profits')
@section('sub-title', 'Import')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/profits')}}"><i class="fa fa-money"></i>Profits</a></li>
			<li>Import</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Import</h3>
					</div>
					<div class="panel-body">
						@include('admin.messages')
						<form id="profits-form" class="form-horizontal label-left" action="{{url('admin/preview-profits-import-file')}}" enctype="multipart/form-data" method="POST">
							@csrf

							<div class="form-group">
								<label for="name" class="col-sm-3 control-label">Import Profit CSV*</label>
								<div class="col-sm-9">
									<input type="file" name="profits-file" class="form-control" accept=".csv" required="">
								</div>
							</div>

							<div class="text-right">
								<a href="{{url('admin/profits')}}">
									<button type="button" class="btn cancel btn-fullrounded">
										<span>Cancel</span>
									</button>
								</a>

								<button type="submit" class="btn btn-primary btn-fullrounded">
									<span>Preview</span>
								</button>
							</div>
						</form>
					</div>
					@if(!empty($profit_import_sheet_data))
					<form id="import-profits-form" class="form-horizontal label-left" action="{{url('admin/profits')}}" enctype="multipart/form-data" method="POST">
						@csrf
						<div class="panel-body">
							<table id="email-templates-datatable" class="table table-hover " style="width:100%">
								<thead>
									<tr>
										<th>YYMM</th>
										<th>Customer Id</th>
										<th>Pool Id</th>
										<th>Investment Id</th>
										<th>Amount</th>
										<th>Profit Percentage</th>
										<th><input type="checkbox" name="select-all" id="select-all" /></th>
									</tr>
								</thead>
								<tbody>
									@foreach ( $profit_import_sheet_data as $key => $row)
									<tr>
										<td>{{$row[0]}}</td>
										<td>{{$row[1]}}</td>
										<td>{{$row[2]}}</td>
										<td>{{$row[3]}}</td>
										<td>{{$row[4]}}</td>
										<td>{{$row[5]}}</td>
										<td><input type="checkbox" name="profits[]" id="checkbox-{{$key}}" value="{{json_encode($row)}}" /></td>
									</tr>
									@endforeach
								</tbody>
							</table>
							<input type="hidden" name="excel_import_file" value="{{$filename}}">
						
							<div id="profit-checkbox-error-div"></div>
							<div class="text-right btn-save">
								<button type="submit" class="btn btn-primary btn-fullrounded">
									<span>Import</span>
								</button>
							</div>
						</div>

					</form>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	$(function() {
		$('#profits-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: true,

			highlight: function(e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function(e) {
				$(e).closest('.form-group').removeClass('has-error');
				$(e).remove();
			},
			errorPlacement: function(error, element) {
				if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
					var controls = element.closest('div[class*="col-"]');
					if (controls.find(':checkbox,:radio').length > 1)
						controls.append(error);
					else
						error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
				} else if (element.is('.select2')) {
					error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
				} else if (element.is('.chosen-select')) {
					error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
				} else
					error.insertAfter(element);
			},
			invalidHandler: function(form, validator) {
				$('html, body').animate({
					scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
				}, 500);
			},
			submitHandler: function(form, validator) {
				if ($(validator.errorList).length == 0) {
					document.getElementById("page-overlay").style.display = "block";
					return true;
				}
			}
		});


		$("#import-profits-form").validate({
			rules: {
				"profits[]": {
					required: true,
					minlength: 1
				}
			},
			messages: {
				"profits[]": "Please select at least 1 Record."
			},
			highlight: function(e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function(e) {
				$(e).closest('.form-group').removeClass('has-error');
				$(e).remove();
			},
			errorPlacement: function(error, element) {
				error.appendTo('#profit-checkbox-error-div');
			},
			invalidHandler: function(form, validator) {
				$('html, body').animate({
					scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
				}, 500);
			},
			submitHandler: function(form, validator) {
				if ($(validator.errorList).length == 0) {
					document.getElementById("page-overlay").style.display = "block";
					return true;
				}
			}
		});
		$('#select-all').click(function(event) {
			if (this.checked) {
				// Iterate each checkbox
				$(':checkbox').each(function() {
					this.checked = true;
				});
			} else {
				$(':checkbox').each(function() {
					this.checked = false;
				});
			}
		});
	});
</script>
@endsection