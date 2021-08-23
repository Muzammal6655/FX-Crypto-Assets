@extends('frontend.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="card-group">
        <div class="card">
            <div class="card-header">
                Dashboard
            </div>
            <div class="card-body">
                @if(!CheckKYCStatus())
                    <p>Your documents are under verification.Please wait for Admin approval.</p>
                @else
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Account Balance ({{config('constants.currency')['symbol']}}): <strong>{{ $user->account_balance }}</strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Commission ({{config('constants.currency')['symbol']}}): <strong>{{ $user->commission_total }}</strong></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Profits ({{config('constants.currency')['symbol']}}): <strong>{{ $user->profit_total }}</strong></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Deposits ({{config('constants.currency')['symbol']}}): <strong>{{ $user->deposit_total }}</strong></h5>
                                    <a href="{{ url('/deposits/create') }}" class="btn btn-success">Make New Deposit</a>
                                    <a href="{{ url('/deposits') }}" class="btn btn-primary">Deposits History</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Withdrawals ({{config('constants.currency')['symbol']}}): <strong>{{ $user->withdraw_total }}</strong></h5>
                                    <a href="{{ url('/withdraws/create') }}" class="btn btn-success">Make New Withdraw</a>
                                    <a href="{{ url('/withdraws') }}" class="btn btn-primary">Withdrawals History</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Deposit History</h3>
                            <canvas id="depositChart" style="width:100%;"></canvas>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Withdraw History</h3>
                            <canvas id="withdrawChart" style="width:100%;"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script>
    var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var depositYvalues = JSON.parse("{{$depositYvalues}}");
    var withdrawYvalues = JSON.parse("{{$withdrawYvalues}}");

    new Chart("depositChart", {
      type: "line",
      data: {
        labels: xValues,
        datasets: [{
            fill: true,
            lineTension: 0,
            backgroundColor: "#d0af3e",
            borderColor: "blue",
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
            fill: true,
            lineTension: 0,
            backgroundColor: "#d0af3e",
            borderColor: "blue",
            data: withdrawYvalues
        }]
      },
      options: {
        legend: {display: false},
      }
    });
</script>
@endsection