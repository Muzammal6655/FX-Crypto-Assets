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
        <p style="font-weight: bold;text-align: center;color: #d0af3e">DateTime : {{Carbon\Carbon::now()}}</p>
 
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
        <table cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 0;">
                <thead>
                    <tr>
                        <th style="text-align: left;border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">Transaction Type</th>
                        <th style="text-align: left;border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">Amount</th>
                        {{--<th>Commission</th>--}}
                    </tr>
                </thead>
                <tbody>
                    @foreach( $current_month_statments as $statment)
                    <tr>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">{{ucwords($statment->type)}}</td>
                        <td style="border: 1px solid rgba(0,0,0,.125);padding:8px;width: 425px;">{{number_format($statment->actual_amount,2)}}</td>
                    {{--<td>{{!empty($statment->commission)?$statment->commission:0.0 }}</td>--}}
                    </tr>
                    @endforeach
                </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="width: 425px;"></td>
                <td style="width: 425px;">
                    <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 30px;border-collapse: collapse;">
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;">Total Deposit</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{$total_deposit}}</td>
                        </tr>
 
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
            <table cellpadding="0" cellspacing="0" width="100%" style="padding: 40px 0;">
                    <thead>
 
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;">Total Withdraw</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{$total_withdraw}}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;">Total Investment</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{$total_investment}}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;">Total Profit</td>
                            <td style="border-bottom: 1px solid rgba(0,0,0,.125);padding: 8px 0;text-align: right;font-weight: 700;">{{number_format($total_profit,2)}}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;">Total Commission</td>
                            <td style="padding: 8px 0;text-align: right;font-weight: 700;">{{$total_commission}}</td>
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