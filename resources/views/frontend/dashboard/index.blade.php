@extends('frontend.layouts.app')
@section('title', 'Dashboard')
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
@section('content')
<div class="main-wrapper dashboard-body">
    <div class="container">
        <div class="card-group">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>
                <div class="card-body">
                    @if($user->photo_status == 0 && $user->passport_status == 0 )
                    <p>Please upload your documents for account verification.
                        <a href="{{url('documents')}}" class="btn btn-primary"><i class="fa fa-upload"></i> Upload Documents</a>
                        @elseif(!CheckKYCStatus())
                    <p>Your documents are under verification.Please wait for Admin approval.</p>
                    @else
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Account Balance ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->account_balance,2)}}</strong></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Commission ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->commission_total,2)}}</strong></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Profits ({{config('constants.currency')['symbol']}}): <strong>{{number_format($user->profit_total,2)}}</strong></h5>
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Investment ({{config('constants.currency')['symbol']}}): <strong>{{number_format($total_investments,2)}}</strong></h5>
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
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card deposit-form-des">
                                <form id="deposits-form" method="POST" action="{{url('/monthly-statements')}}" enctype="multipart/form-data">
                                    <div class="card-body">
                                    {{ csrf_field() }}
                                        <input type="text" id='start_month' name="start_month" value="" autocomplete="off">
                                        <input type="text" id='end_month' name="end_month" value=""  autocomplete="off">
                                        <button type="submit" class="btn btn-primary">Download</button>

                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                        </br>
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Deposit History</h3>
                                <canvas id="depositChart" style="width:100%;"></canvas>
                            </div>
                            <div class="col-sm-6">
                                <h3>Withdraw History</h3>
                                <canvas id="withdrawChart" style="width:100%;"></canvas>
                            </div>
                        </div>
                        <br>
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
                fill: false,
                lineTension: 0,
                backgroundColor: "#d0af3e",
                borderColor: "blue",
                data: investmentsYvalues
            }]
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