@extends('frontend.layouts.app')
@section('title', 'Dashboard')
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
@section('content')
<div class="main-wrapper dashboard-body">
    <div class="container">
        @if(empty($user->btc_wallet_address) && $user->otp_auth_status == 0  && $_COOKIE['dialog'] == 0) 
        <div id="dialog" style="display: none" onclick='{{setcookie('dialog',1,0,"/")}}'>
            <div class="btc_wallet">
                <label for="btc_wallet-dialog">BTC Wallet Address is Missing</label>
                <button class='btn  btc-button-dialog' onclick="location.href='{{ url('/profile') }}'">BTC Wallet</button>
            </div>
            <div class="dialog-2FA">
                <label for="2FA">2FA Missing</label>
                <button class='btn  dialog-2fa-button' onclick="location.href='{{ url('/otp-auth/info') }}'">2 FA</button>
            </div>
        </div>
        @elseif($user->otp_auth_status == 0  && $_COOKIE['dialog'] == 0)
        <div id="dialog" style="display: none" onclick='{{setcookie('dialog',1,0,"/")}}'>
            <div class="dialog-2FA">
                <label for="2FA">2FA Missing</label>
                <button class='btn  dialog-2fa-button float-none' onclick="location.href='{{ url('/otp-auth/info') }}'">2 FA</button>
            </div>
        </div>
        @elseif(empty($user->btc_wallet_address)  && $_COOKIE['dialog'] == 0)
        <div id="dialog" style="display: none" onclick='{{setcookie('dialog',1,0,"/")}}'>
            <div class="btc_wallet">
                <label for="btc_wallet-dialog">BTC Wallet Address is Missing</label>
                <button class='btn  btc-button-dialog' onclick="location.href='{{ url('/profile') }}'">BTC Wallet</button>
            </div>
        </div>
        @endif
        <div class="card-group">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>
                <div class="card-body">
                    @include('frontend.messages')
                    @if($user->photo_status == 0 || $user->passport_status == 0 || $user->photo_status == 2 || $user->passport_status == 2)
                    <p class="upload-doc">Please upload your documents for account verification.
                        <a href="{{url('documents')}}" class="btn btn-primary"><i class="fa fa-upload"></i> Upload Documents</a>
                        @elseif(!CheckKYCStatus())
                    <p>Your documents are under verification.Please wait for Admin approval.</p>
                    @else
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                <div class="card bg-primary box mb-2">
                                    <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                    <div class="card-body">
                                         <h3>{{number_format($user->account_balance,4)}}</h3>
                                         <p>Account Balance ({{config('constants.currency')['symbol']}}):</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                <div class="card bg-danger box mb-2">
                                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                                    <div class="card-body">
                                       <h3>{{number_format($user->commission_total,4)}}</h3>
                                       <p>Total Commission ({{config('constants.currency')['symbol']}}):</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                <div class="card bg-warning box mb-2">
                                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                                    <div class="card-body">
                                         <h3>{{number_format($profit_total,4)}}</h3>
                                         <p>Total Profits ({{config('constants.currency')['symbol']}}):</p>
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                <div class="card bg-success box mb-2">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                    <div class="card-body">
                                         <h3>{{number_format($total_investments,4)}}</h3>
                                         <p>Total Investment ({{config('constants.currency')['symbol']}}):</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="card mb-2 mb-lg-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Deposits ({{config('constants.currency')['symbol']}}): <strong>{{number_format($deposit_total,4)}}</strong></h5>
                                        <a href="{{ url('/deposits/create') }}" class="btn btn-yellow">Make New Deposit</a>
                                        <a href="{{ url('/deposits') }}" class="btn btn-primary">Deposits History</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="card mb-2 mb-lg-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Withdrawals ({{config('constants.currency')['symbol']}}): <strong>{{number_format($withdraw_total,4)}}</strong></h5>
                                        <a href="{{ url('/withdraws/create') }}" class="btn btn-yellow">Make New Withdraw</a>
                                        <a href="{{ url('/withdraws') }}" class="btn btn-primary">Withdrawals History</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card deposit-form-des">
                                <h3 class="mb-2">Download Overall Statistics</h3>
                                <form id="deposits-form" method="POST" action="{{url('/monthly-statements')}}" enctype="multipart/form-data">
                                    <div class="card-body d-flex justify-content-between flex-lg-row flex-md-row flex-column">
                                    {{ csrf_field() }}
                                        <input type="text" class="form-control" id='start_month' name="start_month" value="{{\Carbon\Carbon::now()->subMonth()->format('m')}}/{{\Carbon\Carbon::now()->format('Y')}}" autocomplete="off">
                                        <input type="text" class="form-control" id='end_month' name="end_month" value="{{\Carbon\Carbon::now()->format('m')}}/{{\Carbon\Carbon::now()->format('Y')}}"  autocomplete="off">
                                        <button type="submit" class="btn btn-primary">Download</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                        </br>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="graph-wrapper">
                                    <h3>Deposit History</h3>
                                    <canvas id="depositChart" style="width:100%;"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="graph-wrapper">
                                    <h3>Withdraw History</h3>
                                    <canvas id="withdrawChart" style="width:100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Customers History</h3>
                                <canvas id="investmentsChart" style="width:100%;"></canvas>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/jquery-ui.js" type="text/javascript"></script>
<!-- <link href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css" /> -->
<script  type='text/javascript'>
      $(function () {
        $("#dialog").dialog({
            modal: true,
            title: "Missing Credentials",
            height: 'auto',
            width: 'auto',
            align: 'center',
            position: 'center',
            draggable: false,            
        });
        
    });

</script>
<script>

    var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var depositYvalues = JSON.parse("{{$depositYvalues}}");
    var withdrawYvalues = JSON.parse("{{$withdrawYvalues}}");
    var investmentsYvalues = JSON.parse("{{$investmentsYvalues}}");
    var poolInvestmentsProfitYvalues = JSON.parse("{{$poolInvestmentsProfitYvalues}}");
    
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
            },
            
            ]
        },
        options: {
            legend: {
                display: false
            },
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
            legend: {
                display: false
            },
        }
    });

    new Chart("investmentsChart", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                label: 'Investment Amount',
                fill: false,
                lineTension: 0,
                backgroundColor: "#d0af3e",
                borderColor: "blue",
                data: investmentsYvalues
            },
            {
                label: 'Profit Amount',
                fill: false,
                lineTension: 0,
                backgroundColor: "#d0af3e",
                borderColor: "green",
                data: poolInvestmentsProfitYvalues
            }
        ]
        },
        options: {
            legend: {
                display: false
            },
        }
    });
</script>
<script type="text/javascript" src="//code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

<script type="text/javascript" src="https://rawgithub.com/zorab47/jquery.ui.monthpicker/master/jquery.ui.monthpicker.js"></script>
<script>
    $("#start_month").monthpicker({
        Button: false,
        maxDate: 0,
        MonthFormat: "yy-mm",
        autoclose: true,
        onSelect: function(text, inst) {
            var minDate = new Date(inst.selectedYear, inst.selectedMonth + 1, 1);
            //var maxDate = new Date(inst.selectedYear, inst.selectedMonth + 12, 1);
            var maxDate = 0;
            $('#end_month').monthpicker('option', 'minDate', minDate);
            $('#end_month').monthpicker('option', 'maxDate', maxDate);
        }
    });

    $('#end_month').monthpicker({
        Button: false,
        maxDate: 0,
        MonthFormat: "yy-mm",
        autoclose: true
    });
</script>
@endsection