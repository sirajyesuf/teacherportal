<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('students.create');
    }

    public function add(Request $request)
    {
        $this->validator($request->all())->validate();

        $request->request->add(['role' => 'student','role_type' => 3]);

        $user = User::create($request->all());
        
        if($user)
        {
            toastr()->success('Student created Successfully');
            return redirect()->route('home');
        }
        else
        {
            toastr()->error('An error has occurred please try again later.');
            return back();
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],            
        ]);
    }
}
