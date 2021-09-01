<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pool;
use App\Models\Transaction;
use App\Models\Balance;
use App\Models\PoolInvestment;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Hashids;
use DB;

class PoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['pools'] = Pool::where('status','1')->where('start_date','<=',date('Y-m-d'))->where('end_date','>=',date('Y-m-d'))->get();
        return view('frontend.pools.index')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {  
        $id = Hashids::decode($id)[0];
        $user = auth()->user();
        $data['user'] = $user;
        $data['pool'] = Pool::findOrFail($id);
        return view('frontend.pools.view')->with($data);
    }

    public function invest($id)
    {  
        // if(date('d') != 01) 
        // {   
        //     return redirect()->back()->withInput()->withErrors(['error' => 'Pool Investment requests can be received by the 1st of the month.']);
        // }

        $id = Hashids::decode($id)[0];
        $data['user'] = auth()->user();
        $data['pool'] = Pool::findOrFail($id);
        return view('frontend.pools.invest')->with($data);
    }

    public function saveInvestment(Request $request)
    {  
        $validated = $request->validate([
            'invest_amount' => 'required',
        ]);

        $user = auth()->user();
        $pool = Pool::findOrFail($request->pool_id);
        $pool_investments_count = DB::table('pool_investments')
                          ->where('pool_id', '=' , $pool->id )
                          ->distinct('user_id')
                          ->count();

        if(empty(session()->get('investment_request_email_verification_otp')) || session()->get('investment_request_email_verification_otp') != $request->email_code)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Email code is not correct.']);
        }

        if($user->otp_auth_status)
        {
            // Initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');

            // Add the secret key to the user data
            $response = $google2fa->verifyKey($user->otp_auth_secret_key,$request->two_fa_code);

            if(!$response)
            {
               return redirect()->back()->withInput()->withErrors(['error' => '2FA code is not correct.']);
            }
        }
 
        if($pool_investments_count >= $pool->users_limit)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'User limit of pool is exceeded.']);
        }

        if($request->invest_amount > $pool->max_deposits)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Please enter a value less than or equal to '.$pool->max_deposits.'.']);
        }
        
        if($request->invest_amount < $pool->min_deposits)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Please enter amount greater than or equal to '.$pool->min_deposits.'.']);
        }
        
        if( $user->account_balance  >= $request->invest_amount)
        {
            $model = PoolInvestment::create([
                'user_id' => $user->id,
                'pool_id' => $pool->id,
                'deposit_amount' => $request->invest_amount,
                'profit_percentage' => $pool->profit_percentage,
                'management_fee_percentage' => $pool->management_fee_percentage,
                'status' => 0,
            ]);

            $name = $user->name;
            $email = $user->email;
            $link = url('/admin/pool-investments/'.Hashids::encode($model->id));

            // ********************* //
            // Send email to Admin   //
            // ********************* //

            $email_template = EmailTemplate::where('type','investment_request')->first();

            $subject = $email_template->subject;
            $content = $email_template->content;

            $search = array("{{name}}","{{email}}","{{app_name}}","{{link}}");
            $replace = array($name,$email,env('APP_NAME'),$link);
            $content  = str_replace($search,$replace,$content);

            sendEmail(settingValue('contact_email'), $subject, $content);

            session()->forget('investment_request_email_verification_otp');
            
            $request->session()->flash('flash_success', 'Amount has been invested successfully. Please wait for the admin approval.');
                return redirect()->back();
            
        }
        else
        {
            $request->session()->flash('flash_success', 'Please enter amount less than or equal to '.$user->account_balance.'.');
            return redirect()->back();  
        } 
    }

    public function investments()
    { 
        $data['poolInvestments'] = PoolInvestment::where('user_id',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
        return view('frontend.pools.investments')->with($data);
    }

    public function investmentDetail($id)
    { 
        $id = Hashids::decode($id)[0];
        $data['model'] = PoolInvestment::findOrFail($id);
        $data['pools'] = Pool::where('id','!=',$data['model']->pool_id)->get();
        return view('frontend.pools.investment_detail')->with($data);
    }

    public function transfer(Request $request, $id)
    {   
        $id = Hashids::decode($id)[0];
        $model = PoolInvestment::findOrFail($id);
        $pool = Pool::findOrFail($request->pool_id);
        $pool_investments_count = DB::table('pool_investments')
                          ->where('pool_id', '=' , $pool->id)
                          ->distinct('user_id')
                          ->count();

        if($pool_investments_count >=  $pool->users_limit)
        { 
            return redirect()->back()->withInput()->withErrors(['error' => 'User limit of pool is exceeded.']);
        }

        if($model->deposit_amount >= $pool->min_deposits && $model->deposit_amount <= $pool->max_deposits )
        {
            $model->update([
                'pool_id' => $request->pool,
                'profit_percentage' => $pool->profit_percentage,
                'management_fee_percentage' => $pool->management_fee_percentage,
            ]);
        }
        else
        { 
            return redirect()->back()->withInput()->withErrors(['error' => 'Please enter amount greater than or equal to '.$pool->min_deposits.'.']);
        }
        
        $request->session()->flash('flash_success', 'Pool Investment successfully transfer to '.
            $pool->name. '.');
        return redirect()->back();
   }
}

