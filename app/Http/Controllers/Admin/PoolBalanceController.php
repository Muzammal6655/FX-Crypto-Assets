<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        if(!have_right('pool-balances-list'))
            access_denied();

        $data = [];

        if($request->ajax())
        {
            $db_record = PoolBalance::orderBy('created_at','DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('pool', function($row)
            {
                return $row->pool->name;
            });

            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.pool-balances.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!have_right('pool-balances-import'))
            access_denied();

        return view('admin.pool-balances.form');
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

        if (!empty($request->files) && $request->hasFile('pool_balances')) {
            $file = $request->file('pool_balances');
            Excel::import(new PoolBalancesImport, $file);
        }

        $request->session()->flash('flash_success', 'Pool balances file has been imported successfully.');
        return redirect('admin/pool-balances');
    }
}
