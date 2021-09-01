@extends('frontend.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="main-wrapper">
    <div class="container">
        <div class="card-group">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>
                <div class="card-body dashboard-body">
                    @if($user->photo_status == 0 && $user->passport_status == 0 )
                        <p>Please upload your documents for account verification. 
                        <a href="{{url('documents')}}" class="btn btn-primary" ><i class="fa fa-upload"></i> Upload Documents</a>
                    @elseif(!CheckKYCStatus())
                        <p>Your documents are under verification.Please wait for Admin approval.</p>
                    @else
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Account Balance ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->account_balance,2)}}</strong></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Commission ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->commission_total,2)}}</strong></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Profits ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->profit_total,2)}}</strong></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Deposits ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->deposit_total,2)}}</strong></h5>
                                        <a href="{{ url('/deposits/create') }}" class="btn btn-success">Make New Deposit</a>
                                        <a href="{{ url('/deposits') }}" class="btn btn-primary">Deposits History</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Withdrawals ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->withdraw_total,2)}}</strong></h5>
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
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Investments History</h3>
                                <canvas id="investmentsChart" style="width:100%;"></canvas>
                            </div>
                        </div>
                    @endif
                </div>
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