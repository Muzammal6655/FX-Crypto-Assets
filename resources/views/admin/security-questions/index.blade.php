@extends('admin.layouts.app')
@section('title', 'Security Questions')
@section('sub-title', 'Listing')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Security Question</li>
		</ul>
	</div>
	<div class="container-fluid">
		@include('admin.messages')
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Security Question Listing</h3>
				@if(have_right('security-questions-create'))
				<!-- <div class="right">
					<a href="{{url('admin/security-questions/create')}}" class="pull-right">
						<button title="Add" type="button" class="btn btn-primary btn-lg btn-fullrounded">
							<span>Add</span>
						</button>
					</a>
				</div> -->
				@endif
			</div>
			<div class="panel-body">
				<table id="questions-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Questions</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<!-- END DATATABLE -->
	</div>
</div>
@endsection
@section('js')
<script>
	$(function()
    {
		$('#questions-datatable').dataTable(
		{
			sort: false,
			pageLength: 50,
			scrollX: true,
			processing: false,
			language: { "processing": showOverlayLoader()},
			drawCallback : function( ) {
		        hideOverlayLoader();
		    },
			responsive: true,
			// dom: 'Bfrtip',
			lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			serverSide: true,
			ajax: "{{ url('admin/security-questions') }}",
			columns: [
				{data: 'id', name: 'id', orderable: false, searchable: false},
				{data: 'question', name: 'question'},
				{data: 'status', name: 'status'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			]
		}).on( 'length.dt', function () {
			showOverlayLoader();
		}).on('page.dt', function () {
	        showOverlayLoader();
	    }).on( 'order.dt', function () {
		    showOverlayLoader();
		}).on( 'search.dt', function () {
    		showOverlayLoader();
		});
	});
</script>
@endsection