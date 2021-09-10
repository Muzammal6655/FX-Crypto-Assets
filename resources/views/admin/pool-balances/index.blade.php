@extends('admin.layouts.app')
@section('title', 'Pool Balances')
@section('sub-title', 'Listing')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Pool Balances</li>
		</ul>
	</div>
	<div class="container-fluid">
		@include('admin.messages')
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Pool Balances Listing</h3>
				<div class="right csv-buttons">
					@if(have_right('pool-balances-import'))   
					<a href="{{url('admin/pool-balances/create')}}" class="pull-right">
						<button title="Add" type="button" class="btn btn-primary btn-lg btn-fullrounded">
							<span>Import</span>
						</button>
					</a>
					@endif
					<a href="{{ asset('PoolBalancesSample.csv') }}" download="" class="btn-file">
						<button title="Add" type="button" class="btn btn-primary btn-lg btn-fullrounded">
							<span>Download Sample File</span>
						</button>
					</a>
				</div>
			</div>
			<div class="panel-body">
				<table id="pool-balances-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Pool</th>
							<th>YYMM</th>
							<th>EOM Pool Gross</th>
							<th>EOM Pool Net</th>
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
		$('#pool-balances-datatable').dataTable(
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
			ajax: "{{ url('admin/pool-balances') }}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'pool', name: 'pool'},
				{data: 'year_month', name: 'year_month'},
				{data: 'gross_amount', name: 'gross_amount'},
				{data: 'net_amount', name: 'net_amount'},
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