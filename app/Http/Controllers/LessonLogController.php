<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\LogHour;
use Auth;
use DB;
use App\Student;

class LessonLogController extends Controller
{
    public function add(Request $request)
    {
        if($request->ajax()) 
        {
            $rules = array(
                'lesson_note'=>'required|max:250|string',
                'add_lesson_hour'=>'required|numeric',                    
            );

            $messages = [
                'add_lesson_hour.required' => 'The add hour field is required.',
                'lesson_note.required' => 'The Notes field is required.',
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
                return response()->json($result);
            }
            else
            {

                $logHour = new LogHour;
                $logHour->student_id = $request->add_lesson_log_id;
                $logHour->hours = $request->add_lesson_hour;
                $logHour->notes = $request->lesson_note;
                $logHour->created_by = Auth::user()->id;
                $r = $logHour->save();

                $totalHours = DB::table('add_hour_logs')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$request->add_lesson_log_id)
                       ->sum('hours');

                $finishedHours = DB::table('lesson_hour_logs')
                               ->where('lesson_hour_logs.deleted_at',null)
                               ->where('lesson_hour_logs.student_id',$request->add_lesson_log_id)
                               ->sum('hours');

                $hoursRemaining = $totalHours - $finishedHours;

                if($hoursRemaining < 0)
                    $hoursRemaining = 0;
                
                $student = Student::find($request->add_lesson_log_id);
                $student->remaining_hours = $hoursRemaining;
                $student->is_past = ($hoursRemaining) ? 0 : 1;
                $student->save();
                

                if($r)
                {            
                    $result = ['status' => true, 'message' => 'Hours added Successfully', 'data' => []];
                    return response()->json($result);
                }
                else
                {
                    $result = ['status' => false, 'message' => 'Error in saving data', 'data' => []];      
                    return response()->json($result);              
                }
            }
        }
        
    }

    public function update(Request $request)
    {
        if($request->ajax()) 
        {
            $rules = array(
                'lesson_note'=>'required|max:250|string',
                'add_lesson_hour'=>'required|numeric',                    
            );

            $messages = [
                'add_lesson_hour.required' => 'The add hour field is required.',
                'lesson_note.required' => 'The Notes field is required.',
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
                return response()->json($result);
            }
            else
            {

                $logHour = LogHour::find($request->id);                
                $logHour->hours = $request->add_lesson_hour;
                $logHour->notes = $request->lesson_note;
                $logHour->created_by = Auth::user()->id;
                $r = $logHour->save();

                $totalHours = DB::table('add_hour_logs')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$request->add_lesson_log_id)
                       ->sum('hours');

                $finishedHours = DB::table('lesson_hour_logs')
                               ->where('lesson_hour_logs.deleted_at',null)
                               ->where('lesson_hour_logs.student_id',$request->add_lesson_log_id)
                               ->sum('hours');

                $hoursRemaining = $totalHours - $finishedHours;

                if($hoursRemaining < 0)
                    $hoursRemaining = 0;
                
                $student = Student::find($logHour->student_id);
                $student->remaining_hours = $hoursRemaining;
                $student->is_past = ($hoursRemaining) ? 0 : 1;
                $student->save();
                

                if($r)
                {            
                    $result = ['status' => true, 'message' => 'Hours added Successfully', 'data' => []];
                    return response()->json($result);
                }
                else
                {
                    $result = ['status' => false, 'message' => 'Error in saving data', 'data' => []];      
                    return response()->json($result);              
                }
            }
        }
    }

    public function delete(Request $request)
    {
        $model = LogHour::find($request->id);
        $studeId = $model->student_id;
        $model->delete();
        
        $totalHours = DB::table('add_hour_logs')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$studeId)
                       ->sum('hours');

        $finishedHours = DB::table('lesson_hour_logs')
                       ->where('lesson_hour_logs.deleted_at',null)
                       ->where('lesson_hour_logs.student_id',$studeId)
                       ->sum('hours');

        $hoursRemaining = $totalHours - $finishedHours;

        if($hoursRemaining < 0)
            $hoursRemaining = 0;
        
        $student = Student::find($studeId);
        $student->remaining_hours = $hoursRemaining;
        $student->is_past = ($hoursRemaining) ? 0 : 1;
        $r = $student->save();
                
        if($r){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
    }

    public function details(Request $request)
    {
        $model = LogHour::find($request->id);               

        if(isset($model->id)){
            $result = ['status' => true, 'message' => '', 'detail' => $model];
        }
        else
        {
            $result = ['status' => true, 'message' => 'data not found. please try again.', 'detail' => ""];
        }
        return response()->json($result);
    }
}
