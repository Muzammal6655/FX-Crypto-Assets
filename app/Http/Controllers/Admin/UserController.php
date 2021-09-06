<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Timezone;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\Password;
use App\Models\Referral;
use Carbon\Carbon;
use Auth;
use Hashids;
use File;
use Storage;
use Session;
use Hash;
use DB;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if(!have_right('investors-list'))
            access_denied();

        $data = [];

        if($request->ajax())
        {
            $db_record = User::orderBy('created_at','DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('is_approved', function($row)
            {
                $is_approved = '<span class="label label-warning">Pending</span>';
                if ($row->is_approved == 1)
                {
                    $is_approved = '<span class="label label-success">Approved</span>';
                }
                else if ($row->is_approved == 2)
                {
                    $is_approved = '<span class="label label-danger">Rejected</span>';
                }

                return $is_approved;
            });
            $datatable = $datatable->editColumn('status', function($row)
            {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1)
                {
                    $status = '<span class="label label-success">Active</span>';
                }
                else if ($row->status == 2)
                {
                    $status = '<span class="label label-warning">Unverified</span>';
                }
                else if ($row->status == 3)
                {
                    $status = '<span class="label label-danger">Deleted</span>';
                }

                return $status;
            });
            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if(have_right('investors-referrals'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/investors/" . Hashids::encode($row->id).'/referrals').'" title="Referrals"><i class="fa fa-users"></i></a>';
                }

                if(have_right('investors-transactions'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/investors/" . Hashids::encode($row->id).'/transactions').'" title="Transactions"><i class="fa fa-exchange"></i></a>';
                }

                if(have_right('investors-password'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/investors/" . Hashids::encode($row->id).'/password').'" title="Transactions"><i class="fa fa-key"></i> </a>';
                }

                if(have_right('investors-balances'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/investors/" . Hashids::encode($row->id).'/balances').'" title="Balances"><i class="fa fa-money"></i></a>';
                }

                if(have_right('investors-kyc'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/investors/" . Hashids::encode($row->id).'/documents').'" title="KYC"><i class="fa fa-id-card"></i></a>';
                }

                if(have_right('investors-view'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/investors/" . Hashids::encode($row->id)).'" title="View"><i class="fa fa-eye"></i></a>';
                }

                if(have_right('investors-edit'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/investors/" . Hashids::encode($row->id).'/edit').'" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                }

                if(have_right('investors-delete'))
                {
                    $actions .= '&nbsp;<form method="POST" action="'.url("admin/investors/" . Hashids::encode($row->id)).'" accept-charset="UTF-8" style="display:inline">';
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

            $datatable = $datatable->rawColumns(['is_approved','status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.users.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!have_right('investors-create'))
            access_denied();

        $data['user'] = new User();
        $data['timezones'] = Timezone::all();
        $data['countries'] = Country::all();
        $data['action'] = "Add";
        return view('admin.users.form')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!have_right('investors-edit'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "Edit";
        $data['user'] = User::findOrFail($id);
        $data['timezones'] = Timezone::all();
        $data['countries'] = Country::all();
        return view('admin.users.form')->with($data);
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

        if($input['action'] == 'Add')
        {
            $validator = Validator::make($request->all(), [
                'email' => ['required','string','max:100',Rule::unique('users')],
                'name' => ['required','string','max:100'],
                'password' => 'required|string|min:8|max:30',
            ]);

            if ($validator->fails())
            {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            $input['original_password'] = $input['password'];
            $input['password'] = Hash::make($input['password']);

            $model = new User();
            $flash_message = 'Investor has been created successfully.';
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'email' => ['required','string',Rule::unique('users')->ignore($input['id'])],
                'name' => ['required','string','max:100'],
                'password' => 'required|string|min:8|max:30',
            ]);

            if ($validator->fails())
            {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            $model = User::findOrFail($input['id']);
            $flash_message = 'Investor has been updated successfully.';

            if(!empty($input['password']))
            {
                $input['original_password'] = $input['password'];
                $input['password'] = Hash::make($input['password']);

                /**
                 * Old password keeping 
                 */

                if($request->password != $model->original_password)
                {
                    $password = Password::where(['user_id' => $model->id, 'password' => $request->password])->first();
                    if(!empty($password))
                    {
                        Session::flash('flash_danger', "You have already used this password. Please choose a different one.");
                        return redirect()->back()->withInput();
                    }

                    Password::updateOrCreate(
                        [
                            'user_id' => $model->id,
                            'password' => $model->original_password
                        ],
                        [
                            'user_id' => $model->id,
                            'password' => $model->original_password
                        ]
                    );
                }
            }
            else
            {
                unset($input['password']);
            }
        }

        
        $model->deleted_at = ($input['status'] == "3") ? date("Y-m-d H:i:s") : Null;
        if ($request->is_approved == 1 && $model->is_approved == 0) 
        { 
            $name = $model->name;
            $email = $model->email;
            $email_template = EmailTemplate::where('type','account_approval')->first();

            $subject = $email_template->subject;
            $content = $email_template->content;

            $search = array("{{name}}","{{email}}","{{app_name}}");
            $replace = array($name,$email,env('APP_NAME'));
            $content  = str_replace($search,$replace,$content);

            sendEmail($email, $subject, $content);
        }
        $model->fill($input);
        $model->save();

        if($input['action'] == 'Add')
        {
            $model->invitation_code = Hashids::encode($model->id);
            $model->referral_code_end_date = date("Y-m-t", strtotime("+1 month"));
            $model->save();
        }

        $request->session()->flash('flash_success', $flash_message);
        return redirect('admin/investors');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!have_right('investors-view'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "View";
        $data['user'] = User::findOrFail($id);
        return view('admin.users.view')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id, Request $request)
    {
        if(!have_right('investors-delete'))
            access_denied();

        $id = Hashids::decode($id)[0];
        User::destroy($id);
        Session::flash('flash_success', 'Investor has been deleted successfully.');

        if($request->has('page') && $request->page == 'dashboard' )
        {
            return redirect('admin/dashboard');
        }
        else
        {
            return redirect('admin/investors');
        }
    }

    public function sendPassword($id)
    {
        $user = User::find(Hashids::decode($id)[0]);
        $name = $user->name;
        $email = $user->email;

        $email_template = EmailTemplate::where('type','send_password')->first();
        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}","{{password}}","{{app_name}}");
        $replace = array($name,$user->original_password,env('APP_NAME'));
        $content  = str_replace($search,$replace,$content);

        sendEmail($email, $subject, $content);

        Session::flash('flash_success', 'Password has been sent successfully.');
        return redirect('admin/investors/'.$id.'/edit');
    }

    /**
     * Show the form for kyc verification of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function documents($id)
    {
        if(!have_right('investors-kyc'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['user'] = User::findOrFail($id);
        return view('admin.users.documents')->with($data);
    }

    public function verifyDocuments(Request $request)
    {
        $input = $request->all();

        $model = User::findOrFail($input['id']);
        $model->fill($input);
        $model->save();

        $request->session()->flash('flash_success', 'Documents verification has been updated successfully.');
        return redirect('admin/investors');
    }

    public function balances(Request $request,$id)
    {
        
        if(!have_right('investors-balances'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);
        
        if ($request->ajax())
        {
            $db_record = Balance::where('user_id',$id)->orderBy('id','DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('type', function($row)
            {
                return ucwords($row->type);
            });

            $datatable = $datatable->editColumn('amount', function($row)
            {
                $amount = '';
                if ($row->amount <= 0)
                {
                    $amount = '<span style="color:red">'.$row->amount.'</span>';
                }
                else
                {
                    $amount = '<span style="color:green">+'.$row->amount.'</span>';
                }

                return $amount;
            });

            $datatable = $datatable->editColumn('created_at', function($row)
            {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s') ;
            });
            
            $datatable = $datatable->rawColumns(['amount']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.balances',$data);
    }

    public function transactions(Request $request,$id)
    {
        if(!have_right('investors-transactions'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);
        
        if ($request->ajax())
        {
            $db_record = Transaction::where('user_id',$id)->orderBy('id','DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('created_at', function($row)
            {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s') ;
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if(have_right('transactions-detail'))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/transactions/" . Hashids::encode($row->id).'/detail').'" title="Edit"><i class="fa fa-eye"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });
            
            $datatable = $datatable->rawColumns(['status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.transactions',$data);
    }

    public function referrals(Request $request,$id)
    {
        if(!have_right('investors-referrals'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);
        
        if ($request->ajax())
        {
            $db_record = Referral::where('referrer_id',$id)->orderBy('id','DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('name', function($row)
            {
                return $row->referMember->name;
            });

            $datatable = $datatable->addColumn('email', function($row)
            {
                return $row->referMember->email;
            });

            $datatable = $datatable->editColumn('created_at', function($row)
            {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s');
            });
            
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.referrals',$data);
    }

    public function transactionDetail($id)
    {
        if(!have_right('transactions-detail'))
            access_denied();
     
        $id = Hashids::decode($id)[0];
        $data['model'] = Transaction::findOrFail($id);
        return view('admin.users.transaction_detail')->with($data);
    }

    public function enableLogin($id)
    {    
        $user = User::find(Hashids::decode($id)[0]);

        $user->update([
                'otp_attempts_date' => null,
                'password_attempts_date' => null,
        ]);
 
        Session::flash('flash_success', 'Login has been enabled successfully.');
        return redirect('admin/investors/'.$id.'/edit');
    }

    public function password(Request $request,$id)
    {    
        if(!have_right('investors-password'))
            access_denied();
         
        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);
       
        if ($request->ajax())
        {
            $db_record = Password::where('user_id',$id)->orderBy('id','DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('password', function($row)
            {
                return ucwords($row->password);
            });

            $datatable = $datatable->editColumn('created_at', function($row)
            {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s') ;
            });
            
            $datatable = $datatable->rawColumns(['amount']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.password',$data);
    }
}
