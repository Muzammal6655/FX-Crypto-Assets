<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Deposit;
use App\Models\Pool;
use Hashids;
use Session;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['deposits'] = Deposit::where('user_id',auth()->user()->id)->orderBy('created_at','DESC')->paginate(5);
        return view('frontend.deposits.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = array();

        if($request->has('pool_id') && !empty($request->pool_id) && $id = Hashids::decode($request->pool_id))
        {
            $pool = Pool::findOrFail($id[0]);
            $data['pool_id'] = $pool->id;
            $data['min_deposits'] = $pool->min_deposits;
            $data['max_deposits'] = $pool->max_deposits;
            $data['wallet_address'] = $pool->wallet_address;
        }
        else
        {
            $data['pool_id'] = '';
            $data['min_deposits'] = 0;
            $data['max_deposits'] = 1000;
            $data['wallet_address'] = settingValue('wallet_address');
        }

        return view('frontend.deposits.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'wallet_address' => 'required',
            'amount' => 'required',
            'transaction_id' => 'required|unique:deposits',
            'proof' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        $model = new Deposit();

        if (!empty($request->files) && $request->hasFile('proof')) {
            $file = $request->file('proof');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/'.$user->id.'/deposits';
            $filename = 'proof-' . uniqid() .'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs($target_path, $filename);
            $input['proof'] = $filename;
        }

        $model->fill($input);
        $model->user_id = $user->id;
        $model->status = 0;
        $model->save();
        $request->session()->flash('flash_success', 'Deposit has been created successfully. Please wait until admin approves your deposit.');
        return redirect('/deposits');
    }

    /**
     * Show the form for creating a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Hashids::decode($id)[0];
        $data['deposit'] = Deposit::findOrFail($id);
        return view('frontend.deposits.view')->with($data);
    }
}
