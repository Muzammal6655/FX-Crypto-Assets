@extends('admin.layouts.app')
@section('title', 'Pools')
@section('sub-title', 'Listing')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Pools</li>
		</ul>
	</div>
	<div class="container-fluid">
		@include('admin.messages')
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Pools Listing</h3>
				@if(have_right('pools-create'))
				<div class="right">
					<a href="{{url('admin/pools/create')}}" class="pull-right">
						<button title="Add" type="button" class="btn btn-primary btn-lg btn-fullrounded">
							<span>Add</span>
						</button>
					</a>
				</div>
				@endif
			</div>
			<div class="panel-body">
				<table id="pools-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Min Deposits ({{config('constants.currency')['symbol']}})</th>
							<th>Max Deposits ({{config('constants.currency')['symbol']}})</th>
							<th>Users Limit</th>
							<th>Profit %</th>
							<th>Fee %</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Days</th>
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
		$('#pools-datatable').dataTable(
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
			ajax: "{{ url('admin/pools') }}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'name', name: 'name'},
				{data: 'min_deposits', name: 'min_deposits'},
				{data: 'max_deposits', name: 'max_deposits'},
				{data: 'users_limit', name: 'users_limit'},
				{data: 'profit_percentage', name: 'profit_percentage'},
				{data: 'management_fee_percentage', name: 'management_fee_percentage'},
				{data: 'start_date', name: 'start_date'},
				{data: 'end_date', name: 'end_date'},
				{data: 'days', name: 'days'},
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