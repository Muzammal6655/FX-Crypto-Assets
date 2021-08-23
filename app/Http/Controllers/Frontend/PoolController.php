<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pool;
use App\Models\Transaction;
use App\Models\Balance;
use App\Models\PoolInvestment;
use Carbon\Carbon;
use Hashids;

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
        $data['pool'] = Pool::findOrFail($id);
        return view('frontend.pools.view')->with($data);
    }

    public function invest($id)
    {  
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
       
        if( $request->invest_amount > $pool->max_deposits)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Please enter a value less than or equal to '.$pool->max_deposits.'.']);
        }
        
        if(  $request->invest_amount  < $pool->min_deposits )
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Please enter amount greater than or equal to '.$pool->min_deposits.'.']);
        }
        
        if(  $user->account_balance  >= $request->invest_amount )
        { 
            $user->update([
                'account_balance' => $user->account_balance - $request->invest_amount,
            ]);

            $transaction_message =   "Amount investment in " . $pool->name;

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'investment',
                'amount' => $request->invest_amount,
                'actual_amount' => $request->invest_amount,
                'description' => $transaction_message,
            ]);

            Balance::create([
                'user_id' => $user->id,
                'type' => 'investment',
                'amount' => -1 * $request->invest_amount,
            ]);
     
            PoolInvestment::create([
                'user_id' => $user->id,
                'pool_id' => $pool->id,
                'deposit_amount' => $request->invest_amount,
                'profit_percentage' => $pool->profit_percentage,
                'management_fee_percentage' => $pool->management_fee_percentage,
                'start_date' => Carbon::now('UTC')->timestamp,
                'end_date' => Carbon::now('UTC')->addDay($pool->days)->timestamp,
                'status' => 0,
            ]);
            
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
        return view('frontend.pools.investment_detail')->with($data);
    }
}

