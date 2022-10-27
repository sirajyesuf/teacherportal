<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin')->only('index');
    }
    
    public function index(Request $request)
    {
        $users = User::staff()->search($request->q)->paginate(10);

        $users->appends (array ('q' => $request->q));

        $q = '';

        if(isset($request->q))
            $q = $request->q;

        return view('staff.index',compact('users','q'));
    }
}
