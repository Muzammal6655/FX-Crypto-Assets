@extends('admin.layouts.app')
@section('title', 'Deposits')
@section('sub-title', 'Listing')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Deposits</li>
		</ul>
	</div>
	<div class="container-fluid">
		@include('admin.messages')

		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Advance Filters</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<form id="filter-form" class="form-inline filter-form-des" method="GET">
						<div class="col-lg-3 col-md-4 col-sm-4">
							<div class="form-group">
								<select class="form-control" id="user_id">
									<option value="">Select Investor</option>
									@foreach ($users as $user)
										<option value="{{$user->id}}">{{$user->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-4">
							<div class="form-group">
								<select class="form-control" id="status">
									<option value="">Select Status</option>
									@foreach($statuses as $key => $val)
										<option value="{{$key}}">{{$val}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2">
							<a href="{{url('admin/deposits')}}">
								<button type="button" class="btn cancel btn-fullrounded">
									<span>Reset</span>
								</button>
							</a>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2">
							<button type="submit" class="btn btn-primary btn-fullrounded btn-apply">
								<span>Apply</span>
							</button>
						</div>
					</form>
					<div class="col-lg-2 col-md-2 col-sm-2">
						<form action="{{url('admin/deposits/download-csv')}}" method="post">
							@csrf
							<input type="hidden" name="user_id">
							<input type="hidden" name="status">
							<button type="submit" class="btn btn-info btn-fullrounded btn-apply">
								<span>Download CSV</span>
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Deposits Listing</h3>
			</div>
			<div class="panel-body">
				<table id="deposits-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Investor</th>
							<th>Pool</th>
							<th>Amount ({{config('constants.currency')['symbol']}})</th>
							<th>Created At</th>
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
		$('#deposits-datatable').dataTable(
		{
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
			ajax: {
                url: '/admin/deposits',
                data: function (d) {
                    d.user_id = $('#user_id option:selected').val();
                    d.status = $('#status option:selected').val();
                }
            },
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'user', name: 'user'},
				{data: 'pool', name: 'pool'},
				{data: 'amount', name: 'amount'},
				{data: 'created_at', name: 'created_at'},
				{data: 'status', name: 'status'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			]
		}).on('length.dt', function () {
			showOverlayLoader();
		}).on('page.dt', function () {
	        showOverlayLoader();
	    }).on('order.dt', function () {
		    showOverlayLoader();
		}).on('search.dt', function () {
    		showOverlayLoader();
		});

		$('#filter-form').on('submit', function (e) {
			e.preventDefault();
	        $('#deposits-datatable').DataTable().draw();
	    });

	    $('#user_id').change(function () {
	    	$('input[name="user_id"]').val($(this).val());
	    });
	    $('#status').change(function () {
	    	$('input[name="status"]').val($(this).val());
	    });
	});
</script>
@endsection