<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');

        //$this->authorizeResource(User::class,'user');
    }

    public function index(Request $request) {
        return view('admin.dashboard');
    }
}
