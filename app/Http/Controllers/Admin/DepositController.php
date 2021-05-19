<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Hashids;
use Auth;
use DataTables;

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

        if($request->ajax())
        {
            $db_record = Deposit::orderBy('created_at','ASC');
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
}
