<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pool;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Hashids;
use Auth;
use DataTables;

class PoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right('pools-list'))
            access_denied();

        $data = [];

        if($request->ajax())
        {
            $db_record = Pool::orderBy('created_at','ASC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('min_deposits', function($row)
            {   
                $min_deposits =  number_format($row->min_deposits,2);
                return $min_deposits;
            });

            $datatable = $datatable->editColumn('max_deposits', function($row)
            {   
                $max_deposits =  number_format($row->max_deposits,2);
                return $max_deposits;
            });

            $datatable = $datatable->editColumn('status', function($row)
            {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1)
                {
                    $status = '<span class="label label-success">Active</span>';
                }

                return $status;
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if(have_right('pools-edit'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/pools/" . Hashids::encode($row->id).'/edit').'" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                }

                if(have_right('pools-delete'))
                {
                    $actions .= '&nbsp;<form method="POST" action="'.url("admin/pools/" . Hashids::encode($row->id)).'" accept-charset="UTF-8" style="display:inline">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="'.csrf_token().'">';
                    $actions .= '<button class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    $actions .= '<i class="fa fa-trash"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.pools.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!have_right('pools-create'))
            access_denied();

        $data['model'] = new Pool();
        $data['action'] = "Add";
        return view('admin.pools.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'before:end_date',
        ]);

        if ($validator->fails())
        {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        $input = $request->all();

        if($input['action'] == 'Add')
        {
            $model = new Pool();            
            $flash_message = 'Pool has been created successfully.';
        }
        else
        {
            $model = Pool::findOrFail($input['id']);
            $flash_message = 'Pool has been updated successfully.';
        }

        $model->fill($input);
        $model->save();
        $request->session()->flash('flash_success', $flash_message);
        return redirect('admin/pools');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!have_right('pools-edit'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "Edit";
        $data['model'] = Pool::findOrFail($id);
        return view('admin.pools.form')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!have_right('pools-delete'))
            access_denied();

        $id = Hashids::decode($id)[0];
        Pool::destroy($id);
        Session::flash('flash_success', 'Pool has been deleted successfully.');
        return redirect('admin/pools');
    }
}
