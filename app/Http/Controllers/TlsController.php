<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tls;
use Validator;
use Carbon\Carbon;
use Auth;

class TlsController extends Controller
{
    public function add(Request $request)
    {
        if($request->tpl_student_id)
        {
            $tls = new Tls;
            $tls->student_id = $request->tpl_student_id;
            $tls->date = $request->date;
            $tls->program = $request->program;
            $tls->music_day = $request->music_day;
            $tls->music_prog = $request->music_prog;
            $tls->duration = $request->duration;
            $tls->created_by = Auth::user()->id;
            $r = $tls->save();

            if($r)
            {            
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'TLS data saved Successfully!');
                return redirect()->back();
            }
            else
            {
                $request->session()->flash('message.level', 'error');
                $request->session()->flash('message.content', 'An error has occurred please try again later.');
                return redirect()->back();   
            }
        }
        else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'An error has occurred please try again later.');
            return redirect()->back();
        }
    }

    public function details(Request $request)
    {
        $model = Tls::find($request->id);               

        if(isset($model->id)){
            $result = ['status' => true, 'message' => '', 'detail' => $model];
        }
        else
        {
            $result = ['status' => true, 'message' => 'data not found. please try again.', 'detail' => ""];
        }
        return response()->json($result);
    }

    public function update(Request $request)
    {
        if($request->ajax()) {
            
            $rules = array(
                'date' => 'required|date',
                'program' => 'required|string',                
                'music_day' => 'required|string',                
                'music_prog' => 'required|string',                
                'duration' => 'required|numeric',                
            );
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
            }else{
                $parsedDate = Carbon::parse($request->date)->format('Y-m-d');
                
                $tls = Tls::find($request->update_id);
                $tls->date = $parsedDate;
                $tls->program = $request->program;
                $tls->music_day = $request->music_day;
                $tls->music_prog = $request->music_prog;
                $tls->duration = $request->duration;
                $tls->duration = $request->duration;
                $tls->created_by = Auth::user()->id;
                $r = $tls->save();

                if($r)
                {
                    $result = ['status' => true, 'message' => 'TLS update successfully.', 'data' => []];
                }else{
                    $result = ['status' => false, 'message' => 'TLS update fail!', 'data' => []];
                }
            }
            return response()->json($result);
        }
    }

    public function delete(Request $request)
    {
        $model = Tls::find($request->id);
                
        if($model->delete()){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
    }
}
