<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\Transaction;
use App\Models\Balance;
use Carbon\Carbon;
use Session;
use Hashids;
use Auth;
use DataTables;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right('withdraws-list'))
            access_denied();

        $data = [];
        $data['users'] = User::where('status',1)->get();
        $data['statuses'] = array(0 => 'Pending', 1 => 'Approved', 2 => 'Rejected');
        $data['from'] = $from = date('Y-m-d', strtotime("-1 months"));
        $data['to'] = $to = date('Y-m-d');

        if($request->ajax())
        {
            $data['from'] = $from = $request->from . ' 00:00:00';
            $data['to'] = $to = $request->to . ' 23:59:59';

            $db_record = Withdraw::whereBetween('created_at', [$from, $to]);

            if($request->has('user_id') && !empty($request->user_id))
            {
                $db_record = $db_record->where('user_id',$request->user_id);
            }

            if($request->has('status') && $request->status != "")
            {
                $db_record = $db_record->where('status',$request->status);
            }

            $db_record = $db_record->orderBy('created_at','DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('user', function($row)
            {
                return $row->user->name;
            });

            $datatable = $datatable->editColumn('created_at', function($row)
            {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s') ;
            });

            $datatable = $datatable->editColumn('approved_at', function($row)
            {   
                if(!empty($row->approved_at ))
                    return Carbon::createFromTimeStamp(strtotime($row->approved_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s') ;
                return '';
            });

            $datatable = $datatable->editColumn('status', function($row)
            {
                $status = '<span class="label label-warning">Pending</span>';
                if ($row->status == 1)
                {
                    $status = '<span class="label label-success">Approved</span>';
                }
                else if ($row->status == 2)
                {
                    $status = '<span class="label label-danger">Rejected</span>';
                }
                return $status;
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if(have_right('withdraws-view'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/withdraws/" . Hashids::encode($row->id)).'" title="View"><i class="fa fa-eye"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.withdraws.index',$data);
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
         
        $model = Withdraw::findOrFail($input['id']);
        $model->fill($input);
        $model->save();

        $request->session()->flash('flash_success', 'Withdraw has been updated successfully.');
        return redirect('admin/withdraws');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!have_right('withdraws-view'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "View";
        $data['model'] = Withdraw::findOrFail($id);
        return view('admin.withdraws.view')->with($data);
    }

    public function approve(Request $request, $id)
    {
        if(!have_right('withdraws-approve'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $model = Withdraw::findOrFail($id);

        $user = $model->user;

        if($user->account_balance >= $model->amount)
        {
            $user->update([
                'account_balance' => $user->account_balance - $model->amount,
                'withdraw_total' => $user->withdraw_total + $model->amount,
                'account_balance_timestamp' => Carbon::now('UTC')->timestamp,
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $model->amount,
                'actual_amount' => $model->amount,
                'description' => 'Amount has been withdrawn.',
                'withdraw_id' => $model->id
            ]);

            Balance::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => -1 * $model->amount,
            ]);
        }
        else if ($user->account_balance != 0) 
        {
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $model->amount,
                'actual_amount' =>  $user->account_balance,
                'description' => 'Amount of account balance has been withdrawn.',
                'withdraw_id' => $model->id
            ]);

            Balance::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' =>  -1 * $user->account_balance,
            ]);

            $model->actual_amount = $user->account_balance;

            $user->update([
                'account_balance' => 0,
                'withdraw_total' => $user->withdraw_total + $user->account_balance,
                'account_balance_timestamp' => Carbon::now('UTC')->timestamp,
            ]);
        }
        else
        {
            $request->session()->flash('flash_danger', 'Investor has insufficient balance for requested action.');
            return redirect('admin/withdraws');
        }
        
        $model->status = 1;
        $model->actual_amount = $model->amount;
        $model->approved_at = date('Y-m-d H:i:s');
        $model->save();
        $request->session()->flash('flash_success', 'Withdraw has been approved successfully.');
        return redirect('admin/withdraws');
    }

    public function downloadCsv(Request $request)
    {
        $db_record = Withdraw::whereBetween('created_at', [$request->from, $request->to]);

        if($request->has('user_id') && !empty($request->user_id))
        {
            $db_record = $db_record->where('user_id',$request->user_id);
        }

        if($request->has('status') && $request->status != "")
        {
            $db_record = $db_record->where('status',$request->status);
        }

        $db_record = $db_record->get();

        if(!$db_record->isEmpty())
        {
            $filename = 'withdraws-' . date('d-m-Y') . '.csv';
            $file = fopen('php://memory', 'w');
            fputcsv($file, array('Date','Customer Id','Customer Name','Customer Email','Amount'));

            foreach ($db_record as $record) 
            {
                $row = [];
                $row[] = Carbon::createFromTimeStamp(strtotime($record->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s');
                $row[] = $record->user_id;
                $row[] = $record->user->name;
                $row[] = $record->user->email;
                $row[] = $record->amount;

                fputcsv($file, $row);
            }

            // reset the file pointer to the start of the file
            fseek($file, 0);
            // tell the browser it's going to be a csv file
            header('Content-Type: application/csv');
            // tell the browser we want to save it instead of displaying it
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            // make php send the generated csv lines to the browser
            fpassthru($file);
        }
        else
        {
            $request->session()->flash('flash_danger', 'No data available for export.');
            return redirect()->back();
        }
    }
}
