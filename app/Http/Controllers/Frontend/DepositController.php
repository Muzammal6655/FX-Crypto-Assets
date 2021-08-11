<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Deposit;
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
        $data['deposits'] = Deposit::where('user_id',auth()->user()->id)->get();
        return view('frontend.deposits.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['wallet_address'] = settingValue('wallet_address');
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
            'transaction_id' => 'required',
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
}
