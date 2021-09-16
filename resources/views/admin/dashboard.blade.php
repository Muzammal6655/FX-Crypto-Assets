@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('sub-title', 'Overview & Statistics')

@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
	</div>
	<div class="container-fluid">
		@include('admin.messages')
		<div class="row">
			<div class="col-md-12">
				<!-- OVERVIEW -->
				<div class="panel">
					<div class="panel-heading">
						<h3 class="panel-title">Overview</h3>
					</div>
					<div class="panel-body">
						<div class="row margin-bottom-30">
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-user-secret"></i></span>
									<div class="right">
										<span class="value">{{ $roles }}</span>
										<span class="title">Roles</span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-users"></i></span>
									<div class="right">
										<span class="value">{{ $admins }}</span>
										<span class="title">Sub Admins</span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-users"></i></span>
									<div class="right">
										<span class="value">{{ $users }}</span>
										<span class="title">Investors</span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-product-hunt"></i></span>
									<div class="right">
										<span class="value">{{ $pools }}</span>
										<span class="title">Pools</span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-product-hunt"></i></span>
									<div class="right">
										<span class="value">{{ number_format($total_deposits ,4) }} ({{config('constants.currency')['symbol']}}) </span>
										<span class="title">Total Deposits</span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-product-hunt"></i></span>
									<div class="right">
										<span class="value">{{ number_format($total_withdraws ,4)}} ({{config('constants.currency')['symbol']}}) </span>
										<span class="title">Total Withdraws</span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-product-hunt"></i></span>
									<div class="right">
										<span class="value">{{ number_format($total_investments,4) }}({{config('constants.currency')['symbol']}}) </span>
										<span class="title">Total Investment</span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-6">
								<div class="widget-metric_6 animate">
									<span class="icon-wrapper custom-bg-blue"><i class="fa fa-product-hunt"></i></span>
									<div class="right">
										<span class="value">{{ number_format($total_management_fees,4) }}({{config('constants.currency')['symbol']}}) </span>
										<span class="title">Total Management Fees</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row margin-bottom-30">
							
						</div>
					</div>
				</div>
				<!-- END OVERVIEW -->
			</div>
		</div>
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Historical Graphs</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-6">
						<h3>Deposits</h3>
						<canvas id="depositChart" style="width:100%;"></canvas>
					</div>
					<div class="col-sm-6">
						<h3>Withdrawal</h3>
						<canvas id="withdrawChart" style="width:100%;"></canvas>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h3>Investments</h3>
						<canvas id="investmentsChart" style="width:100%;"></canvas>
					</div>
				</div>
			</div>
		</div>
		<br>
		@if(have_right('investors-list') && count($deleted_users) > 0)
		<div class="alert alert-danger persist-alert" role="alert">
			<center>
			Following users will be deleted on specific deletion datetime
			</center>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- DATATABLE -->
				<div class="panel">
					<div class="panel-heading">
						<h3 class="panel-title">Users Listing</h3>
					</div>
					<div class="panel-body">
						<table id="users-deleted-datatable" class="table table-hover " style="width:100%">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Deletion DateTime</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($deleted_users as $user)
								<tr>
									<td>{{$user->id}}</td>
									<td>{{$user->name}}</td>
									<td>{{$user->email}}</td>
									<td>{{\Carbon\Carbon::createFromTimeStamp(strtotime($user->deleted_at), "UTC")->addDays(settingValue('user_deletion_days'))->tz(session('timezone'))->format('d M, Y h:i:s a')}}
									</td>
									<td>
										<span class="label label-danger">Deleted</span>
									</td>
									<td>
										<span class="actions">
											@if(have_right('investors-edit'))
											<a class="btn btn-primary" title="Edit" target="_blank"
												href="{{url('admin/investors/' . Hashids::encode($user->id) . '/edit')}}"><i
											class="fa fa-pencil-square-o"></i></a>
											@endif
											@if(have_right('investors-delete'))
											<form method="POST"
												action="{{url('admin/investors/'.Hashids::encode($user->id)) }}"
												accept-charset="UTF-8" style="display:inline">
												<input type="hidden" name="_method" value="DELETE">
												<input type="hidden" name="page" value="dashboard">
												<input name="_token" type="hidden" value="{{csrf_token()}}">
												<button class="btn btn-danger" title="Delete"
												onclick="return confirm('Are you sure you want to delete this record?');">
												<i class="fa fa-trash"></i>
												</button>
											</form>
											@endif
										</span>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<!-- END DATATABLE -->
			</div>
		</div>
		@endif
	</div>
</div>
@endsection

@section('js')
<script>
	$(function()
    {
		if($("#users-deleted-datatable").length)
		{
			$('#users-deleted-datatable').dataTable(
			{
				sort: false,
				pageLength: 50,
				scrollX: true,
				responsive: true,
				//dom: 'Bfrtip',
				lengthMenu: [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
				language: { "processing": showOverlayLoader()},
				drawCallback : function( ) {
					hideOverlayLoader();
				},
			}).on( 'length.dt', function () {
				showOverlayLoader();
			}).on('page.dt', function () {
				showOverlayLoader();
			}).on( 'order.dt', function () {
				showOverlayLoader();
			}).on( 'search.dt', function () {
				showOverlayLoader();
			});
		}
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script>
    var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var depositYvalues = JSON.parse("{{$depositYvalues}}");
    var withdrawYvalues = JSON.parse("{{$withdrawYvalues}}");
    var investmentsYvalues = JSON.parse("{{$investmentsYvalues}}");

    new Chart("depositChart", {
      type: "line",
      data: {
        labels: xValues,
        datasets: [{
            fill: false,
            lineTension: 0,
            backgroundColor: "#d0af3e",
            borderColor: "green",
            data: depositYvalues
        }]
      },
      options: {
        legend: {display: false},
      }
    });

    new Chart("withdrawChart", {
      type: "line",
      data: {
        labels: xValues,
        datasets: [{
            fill: false,
            lineTension: 0,
            backgroundColor: "#d0af3e",
            borderColor: "red",
            data: withdrawYvalues
        }]
      },
      options: {
        legend: {display: false},
      }
    });

    new Chart("investmentsChart", {
      type: "line",
      data: {
        labels: xValues,
        datasets: [{
            fill: false,
            lineTension: 0,
            backgroundColor: "#d0af3e",
            borderColor: "blue",
            data: investmentsYvalues
        }]
      },
      options: {
        legend: {display: false},
      }
    });
</script>
@endsection
