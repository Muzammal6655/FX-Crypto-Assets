@extends('admin.layouts.app')
@section('title', 'Customers')
@section('sub-title', 'Passwords')
@section('content')
<div class="main-content">
	<div class="content-heading clearfix">
		<ul class="breadcrumb">
			<li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="{{url('admin/customers')}}"><i class="fa fa-user"></i>Customers</a></li>
			<li>Reset Password</li>
		</ul>
	</div>
	<div class="container-fluid">
		<!-- DATATABLE -->
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Customer E-mail</h3>
				<!-- <div class="right">
					<span class="label label-default" style="font-size: 90%;">Current Password: {{$user->original_password}}</span>
				</div> -->
			</div>
			@include('admin.messages')
			<div class="panel-body">
			<form id="forgot-password" class="text-right" action="{{ route('auth.send-reset-link-email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="email" id="email" class="form-control" name="email"  value="{{$user->email}}" readonly placeholder="Email" required >
                </div>                    
				<div class="text-right">
					<a href="{{url('admin/customers')}}">
						<button type="button" class="btn cancel btn-fullrounded">
							<span>Cancel</span>
						</button>
					</a>
					<button type="submit" class="btn btn-primary btn-fullrounded">
						<span>Reset </span>
					</button>
				</div>
            </form>
		</div>
		<!-- END DATATABLE -->
	</div>
</div>
@endsection
@section('js')
<script>
$(function(){
		$('#forgot-password').validate({
            focusInvalid: true,
            submitHandler: function (form,validator) {
            	if($(validator.errorList).length == 0)
            	{
            		document.getElementById("page-overlay").style.display = "block";
            		return true;
            	}
            }
        });
    });

</script>
<!-- <script>
	$(function()
    {
		$('#password-datatable').dataTable(
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
			ajax: "{{url('admin/customers/'.$id.'/password')}}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
				{data: 'password', name: 'password'},
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
</script> -->
@endsection