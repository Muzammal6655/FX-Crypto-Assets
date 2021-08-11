@extends('frontend.layouts.app')
@section('title', 'Create Deposit')
@section('content')

<div class="container">
	<div class="card">
		<div class="card-header">
			Create Deposit
		</div>
		<div class="card-body">
			<h5 class="card-title">Fill the form below to create deposit</h5>
			@include('frontend.messages')
			<form id="deposits-form" method="POST" action="{{url('/deposits')}}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="form-group">
					<label for="wallet_address">Recipient Wallet Address</label>
					<input type="text" class="form-control" id="wallet_address" name="wallet_address" value="{{$wallet_address}}" readonly="">
				</div>
				<div class="form-group">
					<label for="amount">Amount of BTC</label>
					<input type="number" class="form-control" min="0" id="amount" name="amount" required="">
				</div>
				<div class="form-group">
					<label for="transaction_id">Transaction Id</label>
					<input type="text" class="form-control" id="transaction_id" name="transaction_id" required="">
				</div>
				<div class="form-group">
					<label for="proof">Deposit Receipt</label>
					<input type="file" class="form-control" id="proof" name="proof" accept="image/*" required="">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
</div>
@endsection

@section('js')
<script>
	$(function(){
        $('#deposits-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: true,
            
            highlight: function (e) {
            	$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
	            $(e).closest('.form-group').removeClass('has-error');
	            $(e).remove();
            },
            errorPlacement: function (error, element) {
	            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
		            var controls = element.closest('div[class*="col-"]');
		            if (controls.find(':checkbox,:radio').length > 1)
		                    controls.append(error);
		            else
	                    error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
	            } 
	            else if (element.is('.select2')) {
	            	error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
	            } 
	            else if (element.is('.chosen-select')) {
	            	error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
	            } 
	            else
                    error.insertAfter(element);
            },
            invalidHandler: function (form,validator) {
            }
        });
    });

</script>
@endsection
