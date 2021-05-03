@extends('admin.layouts.app')

@section('title', 'Investors')
@section('sub-title', $action.' Investor')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">

		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/investors')}}"><i class="fa fa-user"></i>Investors</a></li>
			<li>{{$action}}</li>
		</ul>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">{{$action}} Investor</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal label-left">

							<h4 class="heading">Basic Information</h4>

							<div class="form-group">
								<label for="name" class="col-sm-3 control-label">Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->name }}">
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-sm-3 control-label">Email</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->email }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Status</label>
								<div class="col-sm-9">
									@if($user->status == 0)
										<span class="label label-warning">Disable</span>
									@elseif($user->status == 1)
										<span class="label label-success">Active</span>
									@elseif($user->status == 2)
										<span class="label label-primary">Unverified</span>
									@elseif($user->status == 3)
										<span class="label label-danger">Deleted</span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Approval Status</label>
								<div class="col-sm-9">
									@if($user->status == 0)
										<span class="label label-warning">Pending</span>
									@elseif($user->status == 1)
										<span class="label label-success">Approved</span>
									@elseif($user->status == 2)
										<span class="label label-danger">Rejected</span>
									@endif
								</div>
							</div>

							<hr>

							<h4 class="heading">Address Information</h4>

							<div class="form-group">
								<label for="street" class="col-sm-3 control-label">Street</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->street }}">
								</div>
							</div>

							<div class="form-group">
								<label for="city" class="col-sm-3 control-label">City</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->city }}">
								</div>
							</div>

							<div class="form-group">
								<label for="postcode" class="col-sm-3 control-label">Zip Code</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->postcode }}">
								</div>
							</div>

							<div class="form-group">
								<label for="country" class="col-sm-3 control-label">Country</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->country->name }}">
								</div>
							</div>
							<div class="form-group">
								<label for="timezone" class="col-sm-3 control-label">Timezone</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" readonly="" value="{{ $user->timezone }}">
								</div>
							</div>

							<div class="text-right">
								<a href="{{url('admin/investors')}}">
									<button type="button" class="btn btn-primary btn-fullrounded">
										<span>Back</span>
									</button>
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
