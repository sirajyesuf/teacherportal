<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\CaseNote;
use Auth;

class CaseNoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $caseNote = '';

        if($request->id)
        {
            $user = Student::find($request->id);
            $caseNote = CaseNote::where('student_id',$request->id)->where('deleted_at',null)->first();
        }

        return view('casenotes.index',compact('user','caseNote'));
    }

    public function update(Request $request)
    {
        $casenote = CaseNote::where('student_id',$request->student_id)->where('deleted_at',null)->first();
        
        if(!$casenote)
            $casenote = new CaseNote;
        $casenote->student_id = $request->student_id;
        $casenote->case_manager_notes = $request->case_manager_notes;
        $casenote->review_manager_notes = $request->review_manager_notes;
        $casenote->updated_by = Auth::user()->id;
        $r = $casenote->save();

        if($r)
        {            
            toastr()->success('Note updated Successfully');
            return redirect()->back();
        }
        else
        {
            toastr()->error('An error has occurred please try again later.');
            return back();
        }

    }
}
