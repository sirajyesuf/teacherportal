<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('auth.users.create');
    }

    public function add(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = User::create($request->all());
        
        if($user)
        {
            toastr()->success('User created Successfully');
            return redirect()->route('staff');
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
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required','string','max:100'],
        ]);
    }

    protected function editValidator(array $data,$user)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,NULL,'. $user->id . ',id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required','string','max:100'],
        ]);   
    }

    public function edit(User $user)
    {
        return view('auth.users.edit',compact('user'));
    }

    public function update(User $user,Request $request)
    {
        $this->editValidator($request->all(),$user)->validate();

        $r = $user->update($request->all());

        if($r)
        {
            toastr()->success('User upted Successfully');
            return redirect()->route('staff');
        }
        else
        {
            toastr()->error('An error has occurred please try again later.');
            return back();
        }
    }

    
}
