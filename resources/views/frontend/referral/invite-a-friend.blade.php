@extends('frontend.layouts.app')
@section('title', 'Invite A Friend')

@section('content')
<div class="container">
    <div class="card-group">
        <div class="card">
            <div class="card-header">
                Invite A Friend
            </div>
            <div class="card-body">
                <h3>Get a referral bonus when you invite a friend!</h3>
                <br>
                <p>If you refer a new customer to IFX the referring customer will be linked to your account by the referring code.<br><br>Whenever the new customer pays IFX management referral bonus equal to 10% on the management fee paid by the new customer to IFX will be credited to the your account.</p>

                <!-- The text field -->
                <div class="form-group">
                    <input type="text" class="form-control" value="{{ url('/register?ref='.Hashids::encode($user->id)) }}" id="referral_code">
                </div>

                <!-- The button used to copy the text -->
                <button onclick="copyToClipboard()" class="btn btn-primary">Copy text</button>

                <!-- ShareThis BEGIN -->
                <div class="sharethis-inline-share-buttons" data-url="{{url('/register?ref='.Hashids::encode($user->id))}}" data-title="Create a free account | {{env('APP_NAME')}}"></div>
                <!-- ShareThis END -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        function copyToClipboard() {
            /* Get the text field */
            var copyText = document.getElementById("referral_code");
            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand("copy");
            /* Alert the copied text */
            //alert("Copied the text: " + copyText.value);
        }
    </script>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5d0b1d8072b51c001144b80b&product=inline-share-buttons' async='async'></script>
@endsection