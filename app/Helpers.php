<?php

function checkImage($path = '', $placeholder = '', $filename = '')
{
    if (empty($placeholder))
    {
        $placeholder = 'placeholder.png';
    }

    if (!empty($path))
    {
        $url = explode('storage', $path);
        $url = public_path().'/storage'.$url[1];

        if (file_exists($url) && !empty($filename))
            return $path;
        else
            return asset(env('PUBLIC_URL').'images/' . $placeholder);
    }
    else
    {
        return asset(env('PUBLIC_URL').'images/' . $placeholder);
    }
}

function sendEmail($email, $subject, $content, $pdf = '', $filename = '', $csv = '')
{
    if(!empty($pdf))
    {
        try {
            \Mail::send('emails.template', ['content' => $content], function ($message) use ($email, $subject, $pdf, $filename)
            {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($email);
                $message->subject($subject);

                if (!empty($pdf))
                    $message->attachData($pdf->output(), $filename);
            });
        }
        catch (\Exception $e)
        {
            \Log::info('Send Email Exception', array(
                'Message' => $e->getMessage()
            ));
        }
    }
    else
    {
        try
        {
            \Mail::queue(new \App\Mail\SendMail($email, $subject, $content, $filename, $csv));
        }
        catch (\Exception $e)
        {
            \Log::info('Send Email Exception', array(
                'Message' => $e->getMessage()
            ));
        }
    }
}

function rights()
{
    $result = DB::table('rights')
            ->select('rights.name as right_name', 'modules.name as module_name')
            ->join('modules', 'rights.module_id', '=', 'modules.id')
            ->where(['rights.status' => 1])
            ->get()
            ->toArray();

    $array = [];

    for ($i = 0; $i < count($result); $i++)
    {
        $array[$result[$i]->module_name][] = $result[$i];
    }
    return $array;
}

function have_right($right_id)
{
    $user = \Auth::user();
    if ($user['role_id'] == 1)
    {
        return true;
    }
    else
    {
        $result = \DB::table('roles')
                ->where('id', $user['role_id'])
                ->whereRaw("find_in_set('".$right_id."',right_ids)")
                ->first();

        if (!empty($result))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

function access_denied()
{
    abort(403, 'You have no right to perform this action.');
}

function settingValue($key)
{
    $setting = \DB::table('settings')->where('option_name', $key)->first();
    if ($setting)
        return $setting->option_value;
    else
        return '';
}

function getCount($tbl, $where = '')
{
    if(!empty($where))
        return \DB::table($tbl)->where($where)->count();
    else
        return \DB::table($tbl)->count();
}

function getRecord($tbl, $where)
{
    $record = \DB::table($tbl)->where($where)->first();
    if ($record)
    {
        return $record;
    }
    else
    {
        return "";
    }
}

function getValue($tbl, $column, $where)
{
    $record = \DB::table($tbl)->where($where)->first();
    if ($record)
    {
        return $record->$column;
    }
    else
    {
        return "";
    }
}

function durationConversion($seconds)
{
    $time = gmdate("H:i:s", $seconds);
    $timeArr = explode(':', $time);
    $durationStr = '';

    if ($timeArr[0] != '00')
        $durationStr .= $timeArr[0] . ' hr ';
    if ($timeArr[1] != '00')
        $durationStr .= $timeArr[1] . ' min ';
    if ($timeArr[2] != '00')
        $durationStr .= $timeArr[2] . ' sec';

    return $durationStr;
}

function getPreviousMonthDates($from,$to,$timezone)
{
    $to_date = new \Carbon\Carbon($to);
    $from_date = new \Carbon\Carbon($from);

    $diff = $to_date->diffInDays($from_date);

    $prev_to = \Carbon\Carbon::createFromTimeStamp($from,"UTC")->tz($timezone)->subDay()->toDateString();
    $prev_to = $prev_to.' 23:59:59';

    $prev_from = \Carbon\Carbon::createFromTimeStamp(strtotime($prev_to), "UTC")->tz($timezone)->subDays($diff+1)->toDateString();
    $prev_from = $prev_from.' 00:00:00';

    return array(
        'from' => $prev_from,
        'to'   => $prev_to,
    );
}

function formatBytes($size, $precision = 2)
{
    if ($size > 0) {
        $size = (int) $size;
        $base = log($size) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    } else {
        return $size;
    }
}

function convertBytesToGigaBytes($bytes)
{
    return number_format($bytes/1073741824,2);
}

function convertToByte($p_sFormatted) {
    $aUnits = array('B'=>0, 'KB'=>1, 'MB'=>2, 'GB'=>3, 'TB'=>4, 'PB'=>5, 'EB'=>6, 'ZB'=>7, 'YB'=>8);
    $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
    if (intval($sUnit) !== 0) {
        $sUnit = 'B';
    }
    if (!in_array($sUnit, array_keys($aUnits))) {
        return false;
    }
    $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
    if (!intval($iUnits) == $iUnits) {
        return false;
    }
    return $iUnits * pow(1024, $aUnits[$sUnit]);
}

function CheckKYCStatus()
{
    $user = \Auth::user();
    if ($user['photo_status'] == 0 || $user['passport_status'] == 0 || $user['photo_status'] == 2 || $user['passport_status'] == 2 || $user['au_doc_verification_status'] == 2 || $user['au_doc_verification_status'] == 2 )
    {
        return false;
    }
    else
    {
        return true;
    }
}

function MonthStatmentSign($type, $amount)
{
    switch ($type) 
    {
        case $type == 'deposit':
            return   '+ '. number_format($amount,4);
            break;
        case $type == 'investment':
            return      '- '. number_format($amount,4);
            break;
        case $type == 'profit':
            return   '+ '.  number_format($amount,4);
            break;
        case $type == 'commission':
            return    '+ '. number_format($amount,4);
            break; 
        case $type == 'withdraw':
            return   '- '. number_format($amount,4);
            break;
    }

}
?>
