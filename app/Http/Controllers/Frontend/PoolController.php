<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pool;
use Hashids;

class PoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['pools'] = Pool::where('status','1')->where('end_date','>=',date('Y-m-d'))->get();
        return view('frontend.pools.index')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {  
        $id = Hashids::decode($id)[0];
        $data['pool'] = Pool::findOrFail($id);
        return view('frontend.pools.view')->with($data);
    }
}
