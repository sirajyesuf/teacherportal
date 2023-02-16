<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use DB;

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

    public function delete(Request $request)
    {
        $model = User::find($request->id);
        
        if($model->delete()){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
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
        if($request->password)
        {            
            $r = $user->update($request->all());
        }
        else
        {            
            $request->request->remove('password');
            $r = $user->update($request->all());   
        }        

        if($r)
        {
            toastr()->success('User updated Successfully');
            return redirect()->route('staff');
        }
        else
        {
            toastr()->error('An error has occurred please try again later.');
            return back();
        }
    }

    public function getlist(Request $request)
    {
        if($request->name)
        {
            $users = User::select(DB::raw("REPLACE(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')),'  ',' ') as fullname"),'id')
            ->where(DB::raw("REPLACE(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')),'  ',' ')"),'like', '%' . $request->name . '%')
            ->get();

            $userArray = [];
            foreach($users as $key => $user)
            {
                $userArray[$key]['id'] = $key + 1;
                $userArray[$key]['userId'] = $user->id;
                $userArray[$key]['name'] = $user->fullname;                
            }

            return json_encode($userArray);            
        }
    }

    public function getTrainerName(Request $request)
    {
        $search = $request->search;

        $items = DB::table('users')
            ->where('users.deleted_at',null)
            ->where(function ($query) use ($search) {
                $query->where('users.first_name', 'LIKE', '%'.$search.'%')
                      ->orWhere('users.last_name', 'LIKE', '%'.$search.'%');
            })
            ->select('users.id',DB::raw("CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.last_name, '')) AS fullname"))
            ->get();        

        if($items)
        {
            $response = array();
                foreach($items as $key => $item){
                    
                    $response[$key] = array(
                        "id"=>$item->id,
                        "text"=>$item->fullname
                    );
            }
            return response()->json($response); 
        }
    }
    
}
