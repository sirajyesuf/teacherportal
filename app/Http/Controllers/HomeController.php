<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {       
        
        $users = Student::search($request->q)->paginate(3);

        $users->appends (array ('q' => $request->q));

        $q = '';

        if(isset($request->q))
            $q = $request->q;           

        return view('students.index', compact('users','q'));
    }
}
