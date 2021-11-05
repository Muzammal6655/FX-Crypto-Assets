<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Timezone;
use App\Models\Country;
use App\Models\PasswordReset;
use App\Models\EmailTemplate;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\Password;
use App\Models\kycDocuments;
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

        if (!have_right('customers-list'))
            access_denied();

        $data = [];

        if ($request->ajax()) {
            $db_record = User::orderBy('created_at', 'DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('is_approved', function ($row) {
                $is_approved = '<span class="label label-warning">Pending</span>';
                if ($row->is_approved == 1) {
                    $is_approved = '<span class="label label-success">Approved</span>';
                } else if ($row->is_approved == 2) {
                    $is_approved = '<span class="label label-danger">Rejected</span>';
                }

                return $is_approved;
            });
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Active</span>';
                } else if ($row->status == 2) {
                    $status = '<span class="label label-warning">Unverified</span>';
                } else if ($row->status == 3) {
                    $status = '<span class="label label-danger">Deleted</span>';
                }

                return $status;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('customers-referrals')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/referrals') . '" title="Referrals"><i class="fa fa-users"></i></a>';
                }

                if (have_right('kyc-document-history')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/document-history') . '" title="Document History"><i class="fa fa-history"></i></a>';
                }

                if (have_right('customers-transactions')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/transactions') . '" title="Transactions"><i class="fa fa-exchange"></i></a>';
                }

                if (have_right('customers-password')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/password') . '" title="Password"><i class="fa fa-key"></i> </a>';
                }

                if (have_right('customers-balances')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/balances') . '" title="Balances"><i class="fa fa-money"></i></a>';
                }

                if (have_right('customers-kyc')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/documents') . '" title="KYC"><i class="fa fa-id-card"></i></a>';
                }

                if (have_right('customers-view')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id)) . '" title="View"><i class="fa fa-eye"></i></a>';
                }

                if (have_right('customers-edit')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/edit') . '" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                }

                if (have_right('customers-delete')) {
                    $actions .= '&nbsp;<form method="POST" action="' . url("admin/customers/" . Hashids::encode($row->id)) . '" accept-charset="UTF-8" style="display:inline">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    $actions .= '<i class="fa fa-trash"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['is_approved', 'status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!have_right('customers-create'))
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
        if (!have_right('customers-edit'))
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

        if ($input['action'] == 'Add') {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'max:100', Rule::unique('users')],
                'name' => ['required', 'string', 'max:100'],
                'password' => 'required|string|min:8|max:30',
            ]);

            if ($validator->fails()) {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            $input['original_password'] = $input['password'];
            $input['password'] = Hash::make($input['password']);

            $model = new User();
            $flash_message = 'Customer has been created successfully.';
        } else {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', Rule::unique('users')->ignore($input['id'])],
                'name' => ['required', 'string', 'max:100'],
                'password' => 'required|string|min:8|max:30',
            ]);

            if ($validator->fails()) {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            $model = User::findOrFail($input['id']);
            $flash_message = 'Customer has been updated successfully.';
            
            /**
             * Email send to new email address and update email 
             */
            if($input['email'] != $model->email)
            {
                $passwordReset = PasswordReset::updateOrCreate(
                    ['email' => $input['email']],
                    [
                        'email' => $input['email'],
                        'token' => \Str::random(60)
                    ]
                );  
               
                $name = $model->name;
                $email = $input['email'];
                $reset_link = url('/reset-password/' . $passwordReset->token);
              
                $email_template = EmailTemplate::where('type', 'reset_password')->first();
          
                $subject = $email_template->subject;
                $content = $email_template->content;

                $search = array("{{name}}", "{{link}}", "{{app_name}}");
                $replace = array($name, $reset_link, env('APP_NAME'));
                $content  = str_replace($search, $replace, $content);

                sendEmail($email, $subject, $content);

                /**
                 * Email send to previous Email address 
                 */
                $name = $model->name;
                $email = $model->email;
                $email_template = EmailTemplate::where('type', 'email_informed')->first();
            
                $subject = $email_template->subject;
                $content = $email_template->content;

                $search = array("{{name}}", "{{email}}", "{{app_name}}");
                $replace = array($name, $email, env('APP_NAME'));
                $content  = str_replace($search, $replace, $content);

                sendEmail($email, $subject, $content);
            }
            //End Email functionality 

            if (!empty($input['password'])) {
                $input['original_password'] = $input['password'];
                $input['password'] = Hash::make($input['password']);

                /**
                 * Old password keeping 
                 */

                if ($request->password != $model->original_password) {
                    $password = Password::where(['user_id' => $model->id, 'password' => $request->password])->first();
                    if (!empty($password)) {
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
            } else {
                unset($input['password']);
            }
        }


        $model->deleted_at = ($input['status'] == "3") ? date("Y-m-d H:i:s") : Null;

        if ($request->otp_auth_status == 0 && $model->otp_auth_secret_key != '') {
            $name = $model->name;
            $email = $model->email;
            $email_template = EmailTemplate::where('type', '2fa_disable')->first();

            $subject = $email_template->subject;
            $content = $email_template->content;
            $model->otp_auth_secret_key = '';

            $search = array("{{name}}", "{{email}}", "{{app_name}}");
            $replace = array($name, $email, env('APP_NAME'));
            $content  = str_replace($search, $replace, $content);

            sendEmail($email, $subject, $content);
        }

        if ($request->is_approved == 1 && $model->is_approved == 0) {
            $name = $model->name;
            $email = $model->email;
            $email_template = EmailTemplate::where('type', 'account_approval')->first();

            $subject = $email_template->subject;
            $content = $email_template->content;

            $search = array("{{name}}", "{{email}}", "{{app_name}}");
            $replace = array($name, $email, env('APP_NAME'));
            $content  = str_replace($search, $replace, $content);

            sendEmail($email, $subject, $content);
        }

        $model->fill($input);
        $model->save();

        if ($input['action'] == 'Add') {
            $model->invitation_code = Hashids::encode($model->id);
            $model->referral_code_end_date = date("Y-m-t", strtotime("+1 month"));
            $model->save();
        }

        $request->session()->flash('flash_success', $flash_message);
        return redirect('admin/customers');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!have_right('customers-view'))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "View";
        $data['user'] = User::findOrFail($id);
        $data["security_questions"] = $data['user']->securityQuestionAnswer;
        // dd($data["security_questions"]);
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
        if (!have_right('customers-delete'))
            access_denied();
        $id = Hashids::decode($id)[0];
        // $model = User::findOrFail($id);
        User::destroy($id);
        Session::flash('flash_success', 'Customer has been deleted successfully.');

        if ($request->has('page') && $request->page == 'dashboard') {
            return redirect('admin/dashboard');
        } else {
            return redirect('admin/customers');
        }
    }

    public function sendPassword($id)
    {
        $user = User::find(Hashids::decode($id)[0]);
        $name = $user->name;
        $email = $user->email;

        $email_template = EmailTemplate::where('type', 'send_password')->first();
        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}", "{{password}}", "{{app_name}}");
        $replace = array($name, $user->original_password, env('APP_NAME'));
        $content  = str_replace($search, $replace, $content);

        sendEmail($email, $subject, $content);

        Session::flash('flash_success', 'Password has been sent successfully.');
        return redirect('admin/customers/' . $id . '/edit');
    }

    /**
     * Show the form for kyc verification of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function documents($id)
    {
        if (!have_right('customers-kyc'))
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
        return redirect('admin/customers');
    }

    public function balances(Request $request, $id)
    {

        if (!have_right('customers-balances'))
            access_denied();


        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);

        if ($request->ajax()) {
            $db_record = Balance::where('user_id', $id)->orderBy('id', 'DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('type', function ($row) {
                return ucwords($row->type);
            });

            $datatable = $datatable->editColumn('amount', function ($row) {
                $amount = '';
                if ($row->amount <= 0) {
                    $amount = '<span style="color:red">' . number_format($row->amount, 4) . '</span>';
                } else {
                    $amount = '<span style="color:green">+' . number_format($row->amount, 4) . '</span>';
                }

                return $amount;
            });

            $datatable = $datatable->editColumn('created_at', function ($row) {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y h:i:s A');
            });

            $datatable = $datatable->rawColumns(['amount']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.balances', $data);
    }

    public function transactions(Request $request, $id)
    {
        if (!have_right('customers-transactions'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);

        if ($request->ajax()) {
            $db_record = Transaction::where('user_id', $id)->orderBy('id', 'DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('type', function ($row) {
                return   $row->type =  ucwords($row->type);
            });

            $datatable = $datatable->editColumn('created_at', function ($row) {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y  h:i:s A');
            });

            $datatable = $datatable->editColumn('actual_amount', function ($row) {
                if (!empty($row->actual_amount))
                    $row->actual_amount =  number_format($row->actual_amount, 4);
                return $row->actual_amount;
            });

            $datatable = $datatable->editColumn('commission', function ($row) {
                if (!empty($row->commission))
                    $row->commission =  number_format($row->commission, 4);
                return $row->commission;
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                if (have_right('transactions-detail')) {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/customers/" . Hashids::encode($row->id) . '/detail') . '" title="Edit"><i class="fa fa-eye"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.transactions', $data);
    }

    public function referrals(Request $request, $id)
    {
        if (!have_right('customers-referrals'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);

        if ($request->ajax()) {
            $db_record = Referral::where('referrer_id', $id)->orderBy('id', 'DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('name', function ($row) {
                return $row->referMember->name;
            });

            $datatable = $datatable->addColumn('email', function ($row) {
                return $row->referMember->email;
            });

            $datatable = $datatable->editColumn('created_at', function ($row) {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s');
            });

            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.referrals', $data);
    }

    public function transactionDetail($id)
    {
        if (!have_right('transactions-detail'))
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
        return redirect('admin/customers/' . $id . '/edit');
    }

    public function password(Request $request, $id)
    {
        if (!have_right('customers-password'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);


        if ($request->ajax()) {
            $db_record = Password::where('user_id', $id)->orderBy('id', 'DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            // $datatable = $datatable->editColumn('current_password', function($row)
            // {
            //     return ucwords($row->user->original_password);
            // });

            $datatable = $datatable->editColumn('password', function ($row) {
                return ucwords($row->password);
            });

            $datatable = $datatable->editColumn('created_at', function ($row) {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s');
            });

            $datatable = $datatable->rawColumns(['amount']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.password', $data);
    }

    public function documentHistory(Request $request, $id)
    {

        if (!have_right('kyc-document-history'))
            access_denied();

        $data = [];
        $data['id'] = $id;
        $id = Hashids::decode($id)[0];
        $data['user'] = User::find($id);
        // $data['db_records'] = kycDocuments::where('user_id',$id)->orderBy('id','DESC')->get();

        if ($request->ajax()) {
            $db_record = kycDocuments::where('user_id', $id)->orderBy('id', 'DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();


            // $datatable = $datatable->editColumn('doc_type', function($row)
            // {

            //     if ($row->doc_type == 1)
            //     {

            //         $doc_type = $row->document.'&nbsp;<a class="fa fa-eye" href="'.checkImage(asset(env("PUBLIC_URL")."storage/users/".$row->user_id."/documents/". $row->document) ,"placeholder.png", $row->document). '"target="_blank"</a>';
            //     }
            //     else if ($row->doc_type == 2)
            //     {
            //        $doc_type = $row->document.'&nbsp;Photo&nbsp;<a class="fa fa-eye" href="'.checkImage(asset(env("PUBLIC_URL")."storage/users/".$row->user_id."/documents/". $row->document) ,"placeholder.png", $row->document). '"target="_blank"</a>';
            //     }
            //     else if ($row->doc_type == 3)
            //     {
            //        $doc_type = $row->document.'&nbsp;Passport&nbsp;<a   class="fa fa-eye" href="'.checkImage(asset(env("PUBLIC_URL")."storage/users/".$row->user_id."/documents/". $row->document) ,"placeholder.png", $row->document).'"   target="_blank" </a>';
            //     }

            //     return $doc_type;
            // });


            // $datatable = $datatable->editColumn('document', function($row)
            // {

            //     if ($row->doc_type == 1)
            //     {
            //         $document = $row->document.'&nbsp;Au Document&nbsp;<a class="fa fa-download" href="'.checkImage(asset(env("PUBLIC_URL")."storage/users/".$row->user_id."/documents/". $row->document) ,"placeholder.png", $row->document). '"download=""</a>';
            //     }
            //     else if ($row->doc_type == 2)
            //     {
            //        $document = $row->document.'&nbsp;Photo&nbsp;<a class="fa fa-download" href="'.checkImage(asset(env("PUBLIC_URL")."storage/users/".$row->user_id."/documents/". $row->document) ,"placeholder.png", $row->document). '"download=""</a>';
            //     }
            //     else if ($row->doc_type == 3)
            //     {
            //        $document = $row->document.'&nbsp;Passport<a  class="fa fa-download" href="'.checkImage(asset(env("PUBLIC_URL")."storage/users/".$row->user_id."/documents/". $row->document) ,"placeholder.png", $row->document).'"   download="" </a>';
            //     }

            //     return $document;
            // });


            $datatable = $datatable->editColumn('created_at', function ($row) {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y H:i:s');
            });


            $datatable = $datatable->addColumn('status', function ($row) {
                $status = '<span class="actions">';

                if (have_right('document-history-view')) {
                    if ($row->doc_type == 1) {
                        $status .=  '&nbsp;<a class="fa fa-download" href="' . checkImage(asset(env("PUBLIC_URL") . "storage/users/" . $row->user_id . "/documents/" . $row->document), "placeholder.png", $row->document) . '"download=""</a>';
                        $status .= '&nbsp;<a  class="fa fa-eye" href="' . checkImage(asset(env("PUBLIC_URL") . "storage/users/" . $row->user_id . "/documents/" . $row->document), "placeholder.png", $row->document) . '" target="_blank"</a>';
                    } else if ($row->doc_type == 2) {
                        $status .=  '&nbsp; <a class="fa fa-download" href="' . checkImage(asset(env("PUBLIC_URL") . "storage/users/" . $row->user_id . "/documents/" . $row->document), "placeholder.png", $row->document) . '"download=""</a>';
                        $status .= '&nbsp;<a  class="fa fa-eye" href="' . checkImage(asset(env("PUBLIC_URL") . "storage/users/" . $row->user_id . "/documents/" . $row->document), "placeholder.png", $row->document) . '" target="_blank"</a>';
                    } else if ($row->doc_type == 3) {
                        $status .= '&nbsp; <a  class="fa fa-download" href="' . checkImage(asset(env("PUBLIC_URL") . "storage/users/" . $row->user_id . "/documents/" . $row->document), "placeholder.png", $row->document) . '"   download="" </a>';
                        $status .=  '&nbsp;<a   class="fa fa-eye" href="' . checkImage(asset(env("PUBLIC_URL") . "storage/users/" . $row->user_id . "/documents/" . $row->document), "placeholder.png", $row->document) . '"   target="_blank" </a>';
                    }
                    return $status;
                }
            });



            $datatable = $datatable->rawColumns(['doc_type', 'document', 'status']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.doc_history', $data);
    }
}