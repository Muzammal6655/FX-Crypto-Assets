<html>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
</head>

<body style="padding:0; margin:0px; background:#fff;font-family: Segoe, 'Segoe UI', 'sans-serif';">
    <div style="width: 100%;max-width: 850px;min-width: 850px;margin: auto;">
   
        <a style="display: block;text-align: center;width: 100%; margin: 0 auto; padding: 20px 0;" href="{{ url('/') }}">
            <img style="text-align: center;" src="https://interestingfx.arhamsoft.org/images/logo.svg" alt="logo">
        </a>
        <h1 style="font-size: 30px; color: #000;background: #fafafa;text-align: center;padding: 10px;">Overall Statistics</h1>
      
                <table cellpadding="0" cellspacing="0" style="padding-top: 30px;width: 500px;">
                    <tr>
                        <td style="padding: 8px 0;font-weight: 700;">Name:</td>
                        <td style="padding: 8px 0;">{{auth()->user()->name}}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 700;">Email:</td>
                        <td style="padding: 8px 0;">{{auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: 700;">City, State, Country:</td>
                        <td style="padding: 8px 0;">{{auth()->user()->city}}, {{auth()->user()->state}},  {{auth()->user()->country->name}}</td>
                    </tr>
                </table>
                @foreach( $monthly_statment as $key=>$value)
                <p style="font-weight: bold;color: #d0af3e; padding: 20px 0 0; margin:0;">Month : {{$key}}</p>
                <table cellpadding="0" cellspacing="0" width="100%" style="padding: 20px 0 20px;">
                    <tr>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">Total Deposits</td>
                        @if(!empty($monthly_statment[$key]['total_deposits']))
                        <td style="border: 1px solid rgba(0,0,0,.125); padding:8px;width: 425px;">{{$monthly_statment[$key]['total_deposits']}}</td>
                        @else
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">0.0</td>
                        @endif
                    </tr>
                    <tr>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">Total Withdraws</td>
                        @if(!empty($monthly_statment[$key]['total_withdraws']))
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">{{$monthly_statment[$key]['total_withdraws']}}</td>
                        @else
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">0.0</td>
                        @endif
                    </tr>
                    <tr>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">Total Monthly Investments</td>
                        @if(!empty($monthly_statment[$key]['total_monthly_investments']))
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">{{$monthly_statment[$key]['total_monthly_investments']}}</td>
                        @else
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">0.0</td>
                        @endif
                    </tr>
                    <tr>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">Total Monthly Investments Profit</td>
                        @if(!empty($monthly_statment[$key]['total_monthly_investments_profit']))
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">{{$monthly_statment[$key]['total_monthly_investments_profit']}}</td>
                        @else
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">0.0</td>
                        @endif
                    </tr>
                    </table>
        @endforeach
                    <table width="100%" style="margin-top: 160px;border-collapse: collapse;font-size: 14px;line-height: 18px;">
                        <tbody>
                            <tr>
                            <td width="50%" style="font-weight: 500;border-top: 1px solid rgba(0,0,0,.125); padding: 8px; text-align: center;">Interesting FX 2021. All Rights Reserved</td>
                        </tr>
                        </tbody>
                    </table>
                        
    </div>
</body>

</html>