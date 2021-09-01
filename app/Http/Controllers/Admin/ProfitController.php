<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProfitFile;
use App\Imports\ProfitsImport;
use Carbon\Carbon;
use Session;
use Hashids;
use Auth;
use DataTables;
use Excel;

class ProfitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right('profits-list'))
            access_denied();

        $data = [];

        if($request->ajax())
        {
            $db_record = ProfitFile::orderBy('created_at','DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('created_at', function($row)
            {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s') ;
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                $actions .= '&nbsp;<a class="btn btn-primary" href="'.asset('storage/profits/'.$row->name).'" title="Download" download=""><i class="fa fa-download"></i></a>';

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['action']);

            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.profits.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!have_right('profits-import'))
            access_denied();

        return view('admin.profits.form');
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

        $model = new ProfitFile();

        if (!empty($request->files) && $request->hasFile('profits')) {
            $file = $request->file('profits');
            Excel::import(new ProfitsImport, $file);

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/profits';
            $filename = 'profit-' . uniqid() .'.'.$file->getClientOriginalExtension();
 
            $path = $file->storeAs($target_path, $filename);
            $model->name = $filename;
            $model->save();
        }

        $request->session()->flash('flash_success', 'Profit file has been imported successfully. Investment records are updated.');
        return redirect('admin/profits');
    }
}
