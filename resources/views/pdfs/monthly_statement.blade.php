<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{env('APP_NAME')}}</title>
</head>

<body style="padding:0; margin:0px; background:#eee;">


    @foreach( $monthly_statment as $key=>$value)
    <p>Month : {{$key}}</p>
    <table>
        <tr>
            <td>Total Deposits</td>
            @if(!empty($monthly_statment[$key]['total_deposits']))
            <td>{{$monthly_statment[$key]['total_deposits']}}</td>
            @else
            <td>0.0</td>
            @endif
        </tr>
        <tr>
            <td>Total Withdraws</td>
            @if(!empty($monthly_statment[$key]['total_withdraws']))
            <td>{{$monthly_statment[$key]['total_withdraws']}}</td>
            @else
            <td>0.0</td>
            @endif
        </tr>
        <tr>
            <td>Total Monthly Investments</td>
            @if(!empty($monthly_statment[$key]['total_monthly_investments']))
            <td>{{$monthly_statment[$key]['total_monthly_investments']}}</td>
            @else
            <td>0.0</td>
            @endif
        </tr>
        <tr>
            <td>Total Monthly Investments Profit</td>
            @if(!empty($monthly_statment[$key]['total_monthly_investments_profit']))
            <td>{{$monthly_statment[$key]['total_monthly_investments_profit']}}</td>
            @else
            <td>0.0</td>
            @endif
        </tr>
    </table>
    @endforeach


</body>

</html>