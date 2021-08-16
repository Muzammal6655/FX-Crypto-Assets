@extends('admin.layouts.app')
@section('title', 'Withdraws')
@section('sub-title', 'Listing')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Withdraws</li>
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
							<a href="{{url('admin/withdraws')}}">
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
						<form action="{{url('admin/withdraws/download-csv')}}" method="post">
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
				<h3 class="panel-title">Withdraws Listing</h3>
			</div>
			<div class="panel-body">
				<table id="withdraws-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Investor</th>
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
		$('#withdraws-datatable').dataTable(
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
                url: '/admin/withdraws',
                data: function (d) {
                    d.user_id = $('#user_id option:selected').val();
                    d.status = $('#status option:selected').val();
                }
            },
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'user', name: 'user'},
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
	        $('#withdraws-datatable').DataTable().draw();
	    });

	    $('#user_id').change(function () {
	    	$('input[name="user_id"]').val($(this).val());
	    });
	    $('#status').change(function () {
	    	$('input[name="status"]').val($(this).val());
	    });

	    /**
	     * Date range picker
	     */

	    var start = moment('{{$from}}');
        var end = moment('{{$to}}');

        function cb(start, end) {
            $('#dateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#from').val(start.format('YYYY-MM-D'));
            $('#to').val(end.format('YYYY-MM-D'));
        }

        $('#dateRange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '{{__('Today')}}': [moment(), moment()],
                '{{__('Yesterday')}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '{{__('Last 7 Days')}}': [moment().subtract(6, 'days'), moment()],
                '{{__('Last 30 Days')}}': [moment().subtract(30, 'days'), moment()],
                '{{__('This Month')}}': [moment().startOf('month'), moment().endOf('month')],
                '{{__('Last Month')}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
	});
</script>
@endsection