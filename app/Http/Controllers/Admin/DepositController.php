<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Balance;
use App\Models\PoolInvestment;
use Carbon\Carbon;
use Session;
use Hashids;
use Auth;
use DataTables;
use DB;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right('deposits-list'))
            access_denied();

        $data = [];
        $data['users'] = User::where('status',1)->get();
        $data['statuses'] = array(0 => 'Pending', 1 => 'Approved', 2 => 'Rejected');
        $data['from'] = $from = date('Y-m-d', strtotime("-1 months")) . ' 00:00:00';
        $data['to'] = $to = date('Y-m-d') . ' 23:59:59';

        if($request->ajax())
        {
            $data['from'] = $from = $request->from . ' 00:00:00';;
            $data['to'] = $to = $request->to . ' 23:59:59';

            $db_record = Deposit::whereBetween('created_at', [$from, $to]);

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

            $datatable = $datatable->addColumn('pool', function($row)
            {
                if(!empty($row->pool_id))
                    return $row->pool->name;
                return '';
            });

            $datatable = $datatable->editColumn('created_at', function($row)
            {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s') ;
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

                if(have_right('deposits-view'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/deposits/" . Hashids::encode($row->id)).'" title="View"><i class="fa fa-eye"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.deposits.index',$data);
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

        $model = Deposit::findOrFail($input['id']);
        $model->fill($input);
        $model->reason = !empty($request->reason_select) ? $request->reason_select : $request->reason;
        $model->save();
        $request->session()->flash('flash_success', 'Deposit has been updated successfully.');
        return redirect('admin/deposits');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!have_right('deposits-view'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "View";
        $data['model'] = Deposit::findOrFail($id);
        return view('admin.deposits.view')->with($data);
    }

    public function approve(Request $request, $id)
    {
        if(!have_right('deposits-approve'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $model = Deposit::findOrFail($id);
        $user = $model->user;
        $user->update([
            'account_balance' => $user->account_balance + $model->amount,
            'deposit_total' => $user->deposit_total + $model->amount,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $model->amount,
            'actual_amount' => $model->amount,
            'description' => 'Amount deposited.',
            'deposit_id' => $model->id
        ]);

        Balance::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $model->amount,
        ]);

        if(!empty($model->pool_id))
        {
            $pool_investments_count = DB::table('pool_investments')
                          ->where('pool_id', '=' , $pool->id )
                          ->distinct('user_id')
                          ->count();
                         
            if($pool_investments_count >= $model->pool->users_limit)
            {
                return redirect()->back()->withInput()->withErrors(['error' => 'User limit of pool is exceeded.']);
            }
            $pool = $model->pool;

            $user->update([
                'account_balance' => $user->account_balance - $model->amount
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'investment',
                'amount' => $model->amount,
                'actual_amount' => $model->amount,
                'description' => 'Amount invested in '.$pool->name.' pool.',
                'deposit_id' => $model->id
            ]);

            Balance::create([
                'user_id' => $user->id,
                'type' => 'investment',
                'amount' => -1 * $model->amount,
            ]);

            PoolInvestment::create([
                'user_id' => $user->id,
                'pool_id' => $model->pool_id,
                'deposit_amount' => $model->amount,
                'profit_percentage' => $pool->profit_percentage,
                'management_fee_percentage' => $pool->management_fee_percentage,
                'start_date' => Carbon::now('UTC')->timestamp,
                'end_date' => Carbon::now('UTC')->addDay($pool->days)->timestamp,
                'status' => 1,
            ]);
        }

        $model->status = 1;
        $model->save();
        $request->session()->flash('flash_success', 'Deposit has been approved successfully.');
        return redirect('admin/deposits');
    }

    public function downloadCsv(Request $request)
    {
        $db_record = Deposit::whereBetween('created_at', [$request->from, $request->to]);

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
            $filename = 'deposits-' . date('d-m-Y') . '.csv';
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
