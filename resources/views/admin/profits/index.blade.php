@extends('admin.layouts.app')
@section('title', 'Profits')
@section('sub-title', 'Listing')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Profits</li>
		</ul>
	</div>
	<div class="container-fluid">
		@include('admin.messages')
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Profits Listing</h3>
				<div class="right csv-buttons">
				@if(have_right('profits-import'))
					<a href="{{url('admin/profits/create')}}">
						<button title="Add" type="button" class="btn btn-primary btn-lg btn-fullrounded">
							<span>Import</span>
						</button>
					</a>
					@endif
					<a href="{{ asset('ProfitSample.csv') }}" download="" class="btn-file">
						<button title="Add" type="button" class="btn btn-primary btn-lg btn-fullrounded">
							<span>Download Sample File</span>
						</button>
					</a>
				</div>
			</div>
			<div class="panel-body">
				<table id="profits-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Uploaded At</th>
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
		$('#profits-datatable').dataTable(
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
			ajax: "{{ url('admin/profits') }}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'name', name: 'name'},
				{data: 'created_at', name: 'created_at'},
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