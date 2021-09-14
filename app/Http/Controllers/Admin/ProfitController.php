<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\ProfitFile;
use App\Imports\ProfitsImport;
use App\Models\PoolInvestment;
use App\Models\Referral;
use App\Models\Balance;
use App\Models\Transaction;
use Carbon\Carbon;
use Session;
use Hashids;
use Auth;
use DataTables;
use Excel;
use DB;

class ProfitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!have_right('profits-list'))
            access_denied();

        $data = [];

        if ($request->ajax()) {
            $db_record = ProfitFile::orderBy('created_at', 'DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('created_at', function ($row) {
                return Carbon::createFromTimeStamp(strtotime($row->created_at), "UTC")->tz(session('timezone'))->format('d M, Y h:i:s A');
            });

            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';

                $actions .= '&nbsp;<a class="btn btn-primary" href="' . asset('storage/profits/' . $row->name) . '" title="Download" download=""><i class="fa fa-download"></i></a>';

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['action']);

            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.profits.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!have_right('profits-import'))
            access_denied();

        return view('admin.profits.form');
    }


    public function previewFile(Request $request)
    {
        if (!empty($request->files) && $request->hasFile('profits-file')) {
            $file = $request->file('profits-file');

            $target_path = 'public/profits';
            $filename = 'profit-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($target_path, $filename);
            $profitImportSheet = Excel::toArray(new ProfitsImport, $file);
            $rows = [];
            $is_empty_value = 0;
            foreach (array_slice($profitImportSheet[0], 1) as $key => $value) {
                if ($value[3] == "" || $value[5] == "" || $value[3] == 0 || $value[5] == 0) {
                    $is_empty_value = 1;
                    break;
                }
                $rows[] = $value;
            }
            if ($is_empty_value == 1) {
                $request->session()->flash('flash_danger',  "please upload an excel sheet without an empty value.");
                return redirect('admin/profits/create');
            }
            $data['profit_import_sheet_data'] = $rows;
            $data['filename'] =  $filename;
            return view('admin.profits.form')->with($data);
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
        // $arr = [];
        foreach ($input['profits'] as $key => $row) {
            $row = json_decode($row, true);

            $investment = PoolInvestment::find($row[3]);

            if (!empty($investment)) {
                $user = $investment->user;
                $profit = $investment->deposit_amount * ($row[5] / 100);
                $management_fee = $profit * ($investment->management_fee_percentage / 100);
                $actual_profit = $profit - $management_fee;
                $commission = 0;

                if (!empty($user->referral_code) && !empty($user->referrer_account_id)) {
                    $referral_account = $user->referrerAccount;
                    $referral_balance = $referral_account->account_balance;

                    $referral = Referral::where(['referrer_id' => $user->referrer_account_id, 'refer_member_id' => $user->id])->first();

                    //referral account balance greater than 0.01

                    if ($referral_balance > 0.01 && $referral_account->status == 1) {
                        $commission = $management_fee * (10 / 100);

                        /**
                         * User Account balance Update in referral case
                         */
                        DB::beginTransaction();
                        try {

                            $referral_account->update([
                                'account_balance' => $referral_account->account_balance + $commission,
                                'commission_total' =>  $referral_account->commission_total + $commission,
                                'account_balance_timestamp' => Carbon::now('UTC')->timestamp,
                            ]);

                            /**
                             * Commission Update in referrals table
                             */

                            $referral->update([
                                'commission' =>  $referral->commission + $commission,
                            ]);

                            /**
                             * Balances table entry in referral case
                             */

                            Balance::create([
                                'user_id' => $referral_account->id,
                                'type' => 'commission',
                                'amount' => $commission,
                            ]);

                            /**
                             * Transactions table entry in referral case
                             */

                            $transaction_message =   "Referral commission earned from " . $user->name . ' (' . $user->email . ')';

                            Transaction::create([
                                'user_id' => $referral_account->id,
                                'type' => 'commission',
                                'amount' => $commission,
                                'actual_amount' => $commission,
                                'description' => $transaction_message
                            ]);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();

                            $request->session()->flash('flash_danger',  $e->getMessage());
                            return redirect('admin/profits');
                        }
                    }
                }

                /**
                 * Pool Investment table update
                 */

                DB::beginTransaction();
                try {
                    $investment->update([
                        'user_id' =>  $user->id,
                        'profit' =>  $actual_profit,
                        'management_fee' => $management_fee - $commission,
                        'commission' => $commission,
                    ]);

                    /**
                     * User table balance Update
                     */

                    $user->update([
                        'account_balance' => $user->account_balance + $actual_profit + $investment->deposit_amount,
                        'profit_total' => $user->profit_total + $actual_profit,
                        'account_balance_timestamp' => Carbon::now('UTC')->timestamp,
                    ]);

                    /**
                     * Balance table entry
                     */

                    Balance::create([
                        'user_id' => $user->id,
                        'type' => 'profit',
                        'amount' => $actual_profit + $investment->deposit_amount,
                    ]);

                    /**
                     * Transaction table entry
                     */

                    $transaction_message =  "Profit earned from " . $investment->pool->name . ' Pool';

                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'profit',
                        'amount' => $profit,
                        'actual_amount' => $actual_profit,
                        'description' => $transaction_message,
                        'fee_percentage' => $investment->management_fee_percentage,
                        'fee_amount' => $management_fee - $commission,
                        'commission' => $commission,
                    ]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    $excelFile = public_path() . '/storage/profits/' . $input['excel_import_file'];
                    if (file_exists($excelFile)) {
                        $target_path = 'public/profits/';

                        Storage::delete($target_path . '/' . $input['excel_import_file']);
                    }
                    $request->session()->flash('flash_danger',  $e->getMessage());
                    return redirect('admin/profits');
                }
            }
        }
        $model = new ProfitFile();
        $model->name = $input['excel_import_file'];
        $model->save();
        $request->session()->flash('flash_success', 'Profit file has been imported successfully. Investment records are updated.');
        return redirect('admin/profits');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $input = $request->all();

    //     $model = new ProfitFile();

    //     if (!empty($request->files) && $request->hasFile('profits')) {
    //         $file = $request->file('profits');
    //         Excel::import(new ProfitsImport, $file);

    //         // *********** //
    //         // Upload File //
    //         // *********** //

    //         $target_path = 'public/profits';
    //         $filename = 'profit-' . uniqid() . '.' . $file->getClientOriginalExtension();

    //         $path = $file->storeAs($target_path, $filename);
    //         $model->name = $filename;
    //         $model->save();
    //     }

    //     $request->session()->flash('flash_success', 'Profit file has been imported successfully. Investment records are updated.');
    //     return redirect('admin/profits');
    // }
}
