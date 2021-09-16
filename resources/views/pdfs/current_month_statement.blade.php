<html>

<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
</head>
<body style="padding:0; margin:0px; background:#fff;font-family: Segoe, 'Segoe UI', 'sans-serif';1">
    <div style="width: 100%;max-width: 850px;min-width: 850px;margin: auto;">
        <a style="display: block;text-align: center;width: 100%; margin: 0 auto; padding: 20px 0;" href="{{ url('/') }}">
            <img style="text-align: center;" src="https://interestingfx.arhamsoft.org/images/logo.svg" alt="logo">
        </a>
        <h1 style="font-size: 30px; color: #000;background: #fafafa;text-align: center;padding: 10px;">Monthly Statement</h1>
        <table cellpadding="0" cellspacing="0" style="padding-top: 30px;width: 500px;">
            <tr>
                <td style="padding: 8px 0;font-weight: bold;">Name:</td>
                <td style="padding: 8px 0;">{{auth()->user()->name}}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                <td style="padding: 8px 0;">{{auth()->user()->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">City, State, Country:</td>
                <td style="padding: 8px 0;">{{auth()->user()->city}}, {{auth()->user()->state}},  {{auth()->user()->country->name}}</td>
            </tr>
        </table>
        <p style="font-weight: bold;text-align: center;color: #d0af3e; margin:0; padding: 30px 0 0px;">Date And Time : {{Carbon\Carbon::now()->tz(auth()->user()->timezone)->format('d M, Y h:i:s A')}}</p>
        <table cellpadding="0" cellspacing="0" width="100%" style="padding: 20px 0 40px;">
                <thead>
                    <tr>
                        <th style="text-align: left;border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;font-size: 25px;">Transaction Type</th>
                        <th style="text-align: left;border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;font-size: 25px;">Amount</th>
                        <th style="text-align: left;border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;font-size: 25px;">Date And Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $current_month_statments as $statment)
                    <tr>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;font-size: 20px;">{{ucwords($statment->type)}}</td>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;font-size: 20px;">{{MonthStatmentSign($statment->type ,$statment->actual_amount )}}</td>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;font-size: 20px;">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($statment->created_at), "UTC")->tz(auth()->user()->timezone)->format('d M, Y h:i:s A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="width: 425px;"></td>
                <td style="width: 425px;">
                    <table cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 0;">
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;font-weight: bold;">Total Deposit</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{number_format($total_deposit,4)}}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;font-weight: bold;">Total Withdraw</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{number_format($total_withdraw,4)}}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;font-weight: bold;">Total Investment</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{number_format($total_investment,4)}}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;font-weight: bold;">Total Profit</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{number_format($total_profit,4)}}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;font-weight: bold;">Total Commission</td>
                            <td style="padding: 8px 0;text-align: right;font-weight: 700;">{{number_format($total_commission,4)}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 150px;border-collapse: collapse;font-size: 14px;line-height: 18px;">
            <tbody>
                <tr>
                    <td width="50%" style="font-weight: 500;border-top: 1px solid rgba(0,0,0,.125); padding: 8px; text-align: center;">Interesting FX 2021. All Rights Reserved</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>