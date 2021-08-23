<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Http\Traits\GraphTrait;
use Auth;
use DB;
use Carbon\Carbon;
use Hashids;
use DataTables;

class DashboardController extends Controller
{
    use GraphTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
    	$data['roles'] = DB::table('roles')->count();
    	$data['admins'] = DB::table('admins')->count();
        $data['users'] = DB::table('users')->count();
    	$data['pools'] = DB::table('pools')->count();
        $data['deleted_users'] = User::where('status',3)->orderBy('name','DESC')->get();

        $deposits = Deposit::where('status',1)->get();
        $withdraws = Withdraw::where('status',1)->get();

        $response = $this->graph($deposits,$withdraws);
        $data['depositYvalues'] = $response['depositYvalues'];
        $data['withdrawYvalues'] = $response['withdrawYvalues'];
    
        return view('admin.dashboard')->with($data);
    }
}
