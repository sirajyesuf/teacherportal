<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\CaseNote;
use Auth;
use DB;

class CaseNoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $caseNote = $finishedHours = $hoursRemaining = '';

        if($request->id)
        {
            $user = Student::find($request->id);
            $caseNote = CaseNote::where('student_id',$request->id)->where('deleted_at',null)->first();

            $totalHours = DB::table('add_hour_logs')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$request->id)
                       ->sum('hours');

            $finishedHours = DB::table('lesson_hour_logs')
                           ->where('lesson_hour_logs.deleted_at',null)
                           ->where('lesson_hour_logs.student_id',$request->id)
                           ->sum('hours');

            $hoursRemaining = $totalHours - $finishedHours;

            if($hoursRemaining < 0)
                $hoursRemaining = 0;

        }

        return view('casenotes.index',compact('user','caseNote','finishedHours','hoursRemaining'));
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
