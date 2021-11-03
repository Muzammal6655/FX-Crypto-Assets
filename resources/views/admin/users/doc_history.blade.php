@extends('admin.layouts.app')
@section('title', 'Customers')
@section('sub-title', 'Document History')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/customers')}}"><i class="fa fa-user"></i>Customers</a></li>
			<li>Document History</li>
		</ul>
	</div>
	<div class="container-fluid">
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Document History</h3>
			</div>

			<div class="panel-body">
				<table id="docu-history-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Document Name</th>
							<th>Action</th>
							<th>Created At</th>
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
		$('#docu-history-datatable').dataTable(
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
			ajax: "{{url('admin/customers/'.$id.'/document-history')}}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
			 
				{data: 'document', name: 'document'},
				{data: 'status', name: 'status'},
				{data: 'created_at', name: 'created_at'},
			]
		}).on('length.dt', function () {
			showOverlayLoader();
		}).on('page.dt', function () {
	        showOverlayLoader();
	    }).on( 'order.dt', function () {
		    showOverlayLoader();
		}).on('search.dt', function () {
    		showOverlayLoader();
		});
	});
</script>
@endsection