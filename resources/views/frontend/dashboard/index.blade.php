@extends('frontend.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="card-group">
        <div class="card">
            <div class="card-header">
                Dashboard
            </div><br><br><br>
            <div class="card-body">

                @if ( !CheckKYCStatus() ) 
                <p>Your documents are under verification.Please wait for Admin approval.</p>
                @else
                <p>You are logged in successfully!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection