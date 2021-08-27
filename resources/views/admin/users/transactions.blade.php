@extends('admin.layouts.app')
@section('title', 'Investors')
@section('sub-title', 'Transactions')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/investors')}}"><i class="fa fa-user"></i>Investors</a></li>
			<li>Transactions</li>
		</ul>
	</div>
	<div class="container-fluid">
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Transactions Listing</h3>
				<div class="right">
					<span class="label label-default" style="font-size: 90%;">{{$user->name.' - '.$user->email}}</span>
				</div>
			</div>
			<div class="panel-body">
				<table id="transactions-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Type</th>
							<th>Amount ({{config('constants.currency')['symbol']}})</th>
                            <th>Actual Amount ({{config('constants.currency')['symbol']}})</th>
                            <th>Fee (%)</th>
                            <th>Fee ({{config('constants.currency')['symbol']}})</th>
                            <th>Commission ({{config('constants.currency')['symbol']}})</th>
                            <th>Created At</th>
                            <th>Action</th>
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
		$('#transactions-datatable').dataTable(
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
			ajax: "{{url('admin/investors/'.$id.'/transactions')}}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'type', name: 'type'},
				{data: 'amount', name: 'amount'},
                {data: 'actual_amount', name: 'actual_amount'},
                {data: 'fee_percentage', name: 'fee_percentage'},
                {data: 'fee_amount', name: 'fee_amount'},
                {data: 'commission', name: 'commission'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
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