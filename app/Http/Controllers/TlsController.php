<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tls;
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
}
