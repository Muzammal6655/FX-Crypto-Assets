<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Pool;
use App\Models\PoolBalance;
use App\Imports\PoolBalancesImport;
use Carbon\Carbon;
use Session;
use Hashids;
use Auth;
use DataTables;
use Excel;

class PoolBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!have_right('pool-balances-list'))
            access_denied();

        $data = [];

        if ($request->ajax()) {
            $db_record = PoolBalance::orderBy('created_at', 'DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('pool', function ($row) {
                return $row->pool->name;
            });
            
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';
                if (have_right('pool-balances-delete')) {
                    $actions .= '&nbsp;<form method="POST" action="' . url("admin/pool-balances/" . Hashids::encode($row->id)) . '" accept-charset="UTF-8" style="display:inline">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn  btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    $actions .= '<i class="fa fa-trash"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }
            $actions .= '</span>';
            return $actions;
            });
            $datatable = $datatable->rawColumns(['action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.pool-balances.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!have_right('pool-balances-import'))
            access_denied();

        return view('admin.pool-balances.form');
    }

    public function previewFile(Request $request)
    {
        if (!empty($request->files) && $request->hasFile('pool_balances')) {
            $file = $request->file('pool_balances');

            $target_path = 'public/pool-balance';
            $filename = 'pool_balances-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($target_path, $filename);
            $poolBalanceImportSheet = Excel::toArray(new PoolBalancesImport, $file);

            $rows = [];
            $is_empty_value = 0;
            foreach (array_slice($poolBalanceImportSheet[0], 1) as $key => $value) {
                if ($value[0] == "" || $value[1] == "" || $value[2] == 0 || $value[3] == 0) {
                    $is_empty_value = 1;
                    break;
                }
                $rows[] = $value;
            }
            if ($is_empty_value == 1) {
                $request->session()->flash('flash_danger',  "please upload an excel sheet without an empty value.");
                return redirect('admin/pool-balances/create');
            }
            $data['pool_balance_import_sheet_data'] = $rows;
            $data['filename'] =  $filename;
            return view('admin.pool-balances.form')->with($data);
        }
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
        // if (!empty($request->files) && $request->hasFile('pool_balances')) {
        //     $file = $request->file('pool_balances');
        //     Excel::import(new PoolBalancesImport, $file);
        // }
        /**changes after client feedback**/

        $validator = Validator::make($request->all(), [
            'pool_id*' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        foreach ($input['pool_id'] as $key => $value) {
            $pool = Pool::find($value);
            if (!empty($pool)) {
                PoolBalance::updateOrCreate(
                    [
                        'pool_id' => $value,
                        'year_month' => $input['year_month'][$key],
                    ],
                    [
                        'pool_id' => $value,
                        'year_month' => $input['year_month'][$key],
                        'gross_amount' =>  $input['gross_amount'][$key],
                        'net_amount' => $input['net_amount'][$key],
                    ]
                );
            }
        }


        $request->session()->flash('flash_success', 'Pool balances file has been imported successfully.');
        return redirect('admin/pool-balances');
    }
    public function destroy($id, Request $request)
    {
        if (!have_right('pool-balances-delete'))
            access_denied();
        $id = Hashids::decode($id)[0];
        // $model = User::findOrFail($id);
        PoolBalance::destroy($id);
        Session::flash('flash_success', 'Record deleted successfully.');

        if ($request->has('page') && $request->page == 'dashboard') {
            return redirect('admin/dashboard');
        } else {
            return redirect('admin/pool-balances');
        }
    }
}
