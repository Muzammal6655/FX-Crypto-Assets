<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['user'] = auth()->user();
        return view('frontend.dashboard.index', $data);
    }
}
