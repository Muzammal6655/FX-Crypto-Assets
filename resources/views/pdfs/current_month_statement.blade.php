<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{env('APP_NAME')}}</title>
</head>

<body style="padding:0; margin:0px; background:#eee;">



 
    <table>
        <tr>
            <th>Transaction Type</th>
            <th>Amount</th>
            <th>Commission</th>
            
        </tr>
        @foreach( $current_month_statments as $statment)
        <tr>
           <td>{{$statment->type}}</td>
           <td>{{$statment->actual_amount}}</td>
           <td>{{!empty($statment->commission)?$statment->commission:0.0 }}</td>
        </tr>
        
        @endforeach
        <tr>
            <td>Total Investment</td>
            <td>--</td>
        </tr>
        <tr>
            <td>Total Deposit</td>
            <td>--</td>
        </tr>
        <tr>
            <td>Total Withdraw</td>
            <td>--</td>
        </tr>
        <tr>
            <td>Total Commission</td>
            <td>--</td>
        </tr>
        <tr>
            <td>Total Profit</td>
            <td>--</td>
        </tr>
    </table>



</body>

</html>