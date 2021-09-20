<!doctype html>
<html lang="en">

<head>
	<title>Admin | @yield('title')</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/themify-icons/css/themify-icons.css') }}">
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/select2/css/select2.min.css') }}">
	<!-- Animate CSS -->
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/css/vendor/animate/animate.min.css') }}">
	<!-- Summernote CSS -->
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/summernote/summernote.css') }}">
	<!-- Datatables CSS -->
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/datatables/css-main/jquery.dataTables.min.css') }}">
	<link rel="stylesheet"
		href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/datatables/css-bootstrap/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet"
		href="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/datatables-tabletools/css/dataTables.tableTools.css') }}">
	<!-- Datepicker CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/css/main.css') }}">
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/css/custom.css') }}">
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/css/chat.css') }}">
	<link rel="stylesheet" href="{{ asset(env('PUBLIC_URL').'admin-assets/css/new-main.css') }}">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="32x32" href="{{ asset(env('PUBLIC_URL').'images/favicon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset(env('PUBLIC_URL').'images/favicon.png') }}">
</head>

<body>
	<div id="page-overlay">
		<div class="page-overlay-loader"></div>
	</div>
	<!-- WRAPPER -->
	<div id="wrapper">
		@include('admin.sections.header')
		@include('admin.sections.sidebar')
		<!-- MAIN -->
		<div class="main">
			<div class="heading hidden-lg visible-xs mbl-head-des">
				<h1 class="page-title">@yield('title')</h1>
				<p class="page-subtitle">@yield('sub-title')</p>
			</div>
			@yield('content')
		</div>
		<!-- END MAIN -->
		<div class="clearfix"></div>
		<footer>
			<div class="container-fluid">
				<p class="copyright">&copy; {{ date('Y') }} By <a href="#"
						target="_blank">Corporate hi-tech</a>. All Rights Reserved.</p>
			</div>
		</footer>
	</div>
	<!-- END WRAPPER -->
	<!-- Javascript -->
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/select2/js/select2.min.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/summernote/summernote.min.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/datatables/js-main/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/datatables/js-bootstrap/dataTables.bootstrap.min.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/vendor/datatables-tabletools/js/dataTables.tableTools.js') }}"></script>

	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/scripts/klorofilpro-common.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/js/jquery.validate.js') }}"></script>
	<script src="{{ asset(env('PUBLIC_URL').'admin-assets/js/custom.js') }}"></script>
	<script type="text/javascript">
		var scrollTopDifference = 70;

		$.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

		if(!$('.alert').hasClass('persist-alert'))
		{
			setTimeout(function() {
		    $('.alert').fadeOut('slow');
			}, 5000);
		}

	    function readURL(input,id,types,error_type_id,media_type) 
		{
	        if (input.files && input.files[0]) 
			{
				var mimeType = input.files[0]['type'];
				var extention = mimeType.split('/');
				var file_type = extention[0];
				extention = extention[1];
				
				if(jQuery.inArray(extention, types) == -1)
				{
					$('input[name="'+id+'"]').val('');
					var error_message = types.join(", ");
					error_message = 'The '+media_type+' must be a file of type: '+media_type+'/' + error_message; 
					$('#'+error_type_id).css('display','block');
					$('#'+error_type_id).html(error_message);
				}
				else
				{
					var size = input.files[0]['size'];
					size = size / 1024;
					var max_size = (file_type == "image") ? {{ config('constants.file_size') }} : {{ config('constants.file_size') }};

					if(size > max_size)
					{
						max_size = max_size / 1024;
						$('input[name="'+id+'"]').val('');
						$('#'+error_type_id).css('display','block');
						$('#'+error_type_id).html('The '+file_type+' size must be '+max_size+'MB or less.');
					}
					else
					{
						$('#'+error_type_id).css('display','none');
						if(file_type == 'image')
						{
							var reader = new FileReader();
							reader.onload = function(e) {
								$('#'+id).attr('src', e.target.result);
							}
							reader.readAsDataURL(input.files[0]);
						}
					}
				}
	        }
	    }

	    function showOverlayLoader()
	    {
	    	document.getElementById("page-overlay").style.display = "block";
	    }

	    function hideOverlayLoader()
	    {
	    	document.getElementById("page-overlay").style.display = "none";
	    }
	</script>
	
	@yield('js')
</body>

</html>