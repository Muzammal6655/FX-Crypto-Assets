@extends('admin.layouts.app')
@section('title', 'Pool Investments')
@section('sub-title', 'Listing')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li>Pool Investments</li>
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
						<div class="col-lg-4 col-md-4 col-sm-4">
                            <input type="hidden" id="from" name="from" value="{{ $from }}">
                            <input type="hidden" id="to" name="to" value="{{ $to }}">

                            <div id="dateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
							<div class="form-group">
								<select class="form-control" id="pool_id">
									<option value="">Select Pool</option>
									@foreach ($pools as $pool)
										<option value="{{$pool->id}}">{{$pool->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="form-group">
								<select class="form-control" id="user_id">
									<option value="">Select Investor</option>
									@foreach ($users as $user)
										<option value="{{$user->id}}">{{$user->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="form-group">
								<select class="form-control" id="status">
									<option value="">Select Status</option>
									@foreach($statuses as $key => $val)
										<option value="{{$key}}">{{$val}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<br><br>
						<div class="col-lg-2 col-md-2 col-sm-2">
							<a href="{{url('admin/pool-investments')}}">
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
						<form action="{{url('admin/pool-investments/download-csv')}}" method="post">
							@csrf
							<input type="hidden" name="user_id">
							<input type="hidden" name="status">
							<input type="hidden" name="pool_id">
							<input type="hidden" name="from">
							<input type="hidden" name="to">
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
				<h3 class="panel-title">Pool Investments Listing</h3>
			</div>
			<div class="panel-body">
				<table id="pool-investments-datatable" class="table table-hover" style="width:100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Pool</th>
							<th>Investor</th>
							<th>Amount ({{config('constants.currency')['symbol']}})</th>
							<th>Profit (%)</th>
							<th>Profit ({{config('constants.currency')['symbol']}})</th>
							<th>Fee (%)</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Approved At</th>
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
		$('#pool-investments-datatable').dataTable(
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
			ajax: {
                url: "{{ route('admin.pool-investments.index') }}",
                data: function (d) {
                    d.user_id = $('#user_id option:selected').val();
                    d.pool_id = $('#pool_id option:selected').val();
					d.status = $('#status option:selected').val();
                    d.from = $('#from').val();
                    d.to = $('#to').val();
                }
            },
			columns: [
				{data: 'id', name: 'id', orderable: false, searchable: false},
				{data: 'pool', name: 'pool'},
				{data: 'user', name: 'user'},
				{data: 'deposit_amount', name: 'deposit_amount'},
				{data: 'profit_percentage', name: 'profit_percentage'},
				{data: 'profit', name: 'profit'},
				{data: 'management_fee_percentage', name: 'management_fee_percentage'},
				{data: 'start_date', name: 'start_date'},
				{data: 'end_date', name: 'end_date'},
				{data: 'approved_at', name: 'approved_at'},
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
	        $('#pool-investments-datatable').DataTable().draw();
	    });

	    $('#user_id').change(function () {
	    	$('input[name="user_id"]').val($(this).val());
	    });
	    $('#pool_id').change(function () {
	    	$('input[name="pool_id"]').val($(this).val());
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
            $('input[name="from"]').val(start.format('YYYY-MM-D'));
            $('input[name="to"]').val(end.format('YYYY-MM-D'));
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