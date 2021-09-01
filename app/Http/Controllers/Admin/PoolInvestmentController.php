<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Pool;
use App\Models\Transaction;
use App\Models\Balance;
use App\Models\PoolInvestment;
use Carbon\Carbon;
use Session;
use Hashids;
use Auth;
use DataTables;
use DB;

class PoolInvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right('pool-investments-list'))
            access_denied();
 
        $data = [];
        $data['users'] = User::where('status',1)->get();
        $data['pools'] = Pool::where('status',1)->get();
        $data['statuses'] = array(0 => 'Pending', 1 => 'Approved', 2 => 'Rejected');
        $data['from'] = $from = date('Y-m-d', strtotime("-1 months"));
        $data['to'] = $to = date('Y-m-d');
 
        if($request->ajax())
        {
            $data['from'] = $from = $request->from . ' 00:00:00';
            $data['to'] = $to = $request->to . ' 23:59:59';
        
            $db_record = PoolInvestment::whereBetween('created_at',[$from,$to]);   
            if($request->has('user_id') && !empty($request->user_id))
            {
                $db_record = $db_record->where('user_id',$request->user_id);
            }

            if($request->has('status') && $request->status != "")
            {
                $db_record = $db_record->where('status',$request->status);
            }

            if($request->has('pool_id') && $request->pool_id != "")
            {
                $db_record = $db_record->where('pool_id',$request->pool_id);
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

            $datatable = $datatable->editColumn('start_date', function($row)
            {   
                if(!empty($row->start_date ))
                    return Carbon::createFromTimeStamp($row->start_date)->tz(session('timezone'))->format('d M, Y') ;
                 return '';
            });

            $datatable = $datatable->editColumn('end_date', function($row)
            {
                if(!empty($row->end_date ))
                    return Carbon::createFromTimeStamp($row->end_date)->tz(session('timezone'))->format('d M, Y') ;
                 return '';
            });

            $datatable = $datatable->editColumn('approved_at', function($row)
            {
                if(!empty($row->approved_at ))
                    return Carbon::createFromTimeStamp(strtotime($row->approved_at), "UTC")->tz(session('timezone'))->format('d M, Y') ;
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

                if(have_right('pool-investments-view'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/pool-investments/" . Hashids::encode($row->id)).'" title="View"><i class="fa fa-eye"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.pool-investments.index',$data);
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

        $model = PoolInvestment::findOrFail($input['id']);
        $model->fill($input);
        $model->reason = !empty($request->reason_select) ? $request->reason_select: $request->reason;
        $model->status = 2;
        $model->save();
        $request->session()->flash('flash_success', 'Pool Invesment has been updated successfully.');
        return redirect('admin/pool-investments');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  
        if(!have_right('pool-investments-view'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "View";
        $data['model'] = PoolInvestment::findOrFail($id);
        return view('admin.pool-investments.view')->with($data);
    }

    public function approve(Request $request, $id)
    {
        if(!have_right('pool-investments-approve'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $model = PoolInvestment::findOrFail($id);
        $pool_investments_count = DB::table('pool_investments')
                          ->where('pool_id', '=' , $model->pool->id )
                          ->distinct('user_id')
                          ->count();

        if($pool_investments_count >= $model->pool->users_limit)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'User limit of pool is exceeded.']);
        }
        else if($model->user->account_balance >= $model->deposit_amount)
        {
            $model->update([
                'start_date' => Carbon::now('UTC')->timestamp,
                'end_date' => Carbon::now('UTC')->addDay($model->pool->days)->timestamp,
                'approved_at' => date('Y-m-d H:i:s'),
                'status' => 1
            ]);

            $model->user->update([
                'account_balance' => $model->user->account_balance - $model->deposit_amount,
                'account_balance_timestamp' => Carbon::now('UTC')->timestamp,
            ]);

            $transaction_message =   "Amount investment in " . $model->pool->name;

            Transaction::create([
                'user_id' => $model->user_id,
                'type' => 'investment',
                'amount' => $model->deposit_amount,
                'actual_amount' => $model->deposit_amount,
                'description' => $transaction_message,
            ]);

            Balance::create([
                'user_id' => $model->user_id,
                'type' => 'investment',
                'amount' => -1 * $model->deposit_amount,
            ]);

            $request->session()->flash('flash_success', 'Pool Investment has been approved successfully.');
            return redirect('admin/pool-investments');   
        }
        else
        {
             $request->session()->flash('flash_danger', 'Investor has insufficient balance for requested action.');
            return redirect('admin/pool-investments'); 
        }
    }

    public function downloadCsv(Request $request)
    {
        $db_record = PoolInvestment::whereBetween('created_at',[$request->from,$request->to]);

        if($request->has('user_id') && !empty($request->user_id))
        {
            $db_record = $db_record->where('user_id',$request->user_id);
        }

        if($request->has('status') && $request->status != "")
        {
            $db_record = $db_record->where('status',$request->status);
        }

        if($request->has('pool_id') && $request->pool_id != "")
        {
            $db_record = $db_record->where('pool_id',$request->pool_id);
        }

        $db_record = $db_record->get();

        if(!$db_record->isEmpty())
        {
            $filename = 'pool-investments-' . date('d-m-Y') . '.csv';
            $file = fopen('php://memory', 'w');
            fputcsv($file, array('Customer Id','Customer Name','Customer Email','Pool','Amount','Profit Percentage','Management Fee Percentage','Commission','Management Fee','Started Date','End Date'));

            foreach ($db_record as $record) 
            {
                $row = [];
                $row[] = $record->user_id;
                $row[] = $record->user->name;
                $row[] = $record->user->email;
                $row[] = $record->pool->name;
                $row[] = $record->deposit_amount;
                $row[] = $record->profit_percentage;
                $row[] = $record->management_fee_percentage;
                $row[] = -$record->commission;
                $row[] = -$record->management_fee;                
                $row[] = Carbon::createFromTimeStamp($record->start_date)->tz(session('timezone'))->format('d M, Y');
                $row[] =  Carbon::createFromTimeStamp($record->end_date)->tz(session('timezone'))->format('d M, Y');


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
