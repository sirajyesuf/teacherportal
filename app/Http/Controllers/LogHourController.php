<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LessonLog;
use App\Lesson;
use Validator;
use Auth;
use DB;
use App\Student;
use Carbon\Carbon;

class LogHourController extends Controller
{
    public function add(Request $request)
    {
        if($request->ajax()) 
        {
            $rules = array(
                'add_log_hour'=>'required|numeric',                    
                'lesson_date'=>'required|date_format:d M Y',
                'name'=>'required',
            );

            $messages = [
                'add_log_hour.required' => 'The add hour field is required.',                                
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
                return response()->json($result);
            }
            else
            {
                // Convert date to db date formate
                $lesson_date = Carbon::createFromFormat('d M Y', $request->lesson_date)->format('Y-m-d');

                $lessonLog = LessonLog::where('lesson_date',$lesson_date)->where('student_id',$request->add_log_student_id)->where('deleted_at',null)->first();

                if(!$request->duplicate)
                {                    
                    if($lessonLog)
                    {
                        $result = ['status' => true, 'match' => 1, 'message' => 'Hours added Successfully', 'data' => []];
                        return response()->json($result);
                    }
                }
                
                if(!$lessonLog){
                    $lessonLog = new LessonLog;                                                    
                }
                $lessonLog->student_id = $request->add_log_student_id;
                $lessonLog->hours = $request->add_log_hour;
                $lessonLog->lesson_date = $lesson_date;
                $lessonLog->created_by = $request->name;
                $r = $lessonLog->save();

                $totalHours = DB::table('add_hour_logs')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$request->add_log_student_id)
                       ->sum('hours');

                $finishedHours = DB::table('lesson_hour_logs')
                               ->where('lesson_hour_logs.deleted_at',null)
                               ->where('lesson_hour_logs.student_id',$request->add_log_student_id)
                               ->sum('hours');

                $hoursRemaining = $totalHours - $finishedHours;

                if($hoursRemaining < 0)
                    $hoursRemaining = 0;

                $student = Student::find($request->add_log_student_id);

                if($hoursRemaining == 0)
                {
                    $student->is_past = 1;                    
                }
                $student->remaining_hours = $hoursRemaining;
                $student->save();

                if($r)
                {            
                    // toastr()->success('Hours added Successfully');
                    $result = ['status' => true, 'message' => 'Hours added Successfully', 'data' => []];
                    return response()->json($result);
                }
                else
                {
                    // toastr()->error('An error has occurred please try again later.');
                    $result = ['status' => false, 'message' => 'Error in saving data', 'data' => []];                    
                    return response()->json($result);
                }
            }
        }
          
    }

    public function details(Request $request)
    {
        if($request->ajax()) 
        {
            $model = LessonLog::find($request->id);

            if(!$model)
            {
                return response()->json(['message' => 'Log Not Found'], 404);
            }
            
            if(isset($model->id)){

                $username = $model->user->first_name.' '.$model->user->last_name;            

                // Convert date to db date formate
                $lesson_date = Carbon::createFromFormat('Y-m-d', $model->lesson_date)->format('d M Y');

                $result = ['status' => true, 'message' => '', 'detail' => $model, 'id' => $model->user->id, 'username' => $username, 'date' => $lesson_date];
            }
            else
            {
                $result = ['status' => true, 'message' => 'data not found. please try again.', 'detail' => ""];
            }
            return response()->json($result);
        }

        return response()->json(['message' => 'Request Error'], 400);
    }

    public function update(Request $request)
    {
        if($request->ajax()) 
        {
            $rules = array(
                'add_log_hour'=>'required|numeric',                    
                'lesson_date'=>'required|date_format:d M Y',
                'name'=>'required',
            );

            $messages = [
                'add_log_hour.required' => 'The add hour field is required.',                                
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
                return response()->json($result);
            }
            else
            {
                // Convert date to db date formate
                $lesson_date = Carbon::createFromFormat('d M Y', $request->lesson_date)->format('Y-m-d');

                $lesson_dateJson = Carbon::createFromFormat('d M Y', $request->lesson_date)->format('d-m-Y');

                $lessonLogDup = LessonLog::where('id','!=',$request->edit_lesson_log_id)->where('lesson_date',$lesson_date)->where('deleted_at',null)->first();

                if(!$request->duplicate)
                {                    
                    if($lessonLogDup)
                    {
                        $result = ['status' => true, 'match' => 1, 'message' => '', 'data' => []];
                        return response()->json($result);
                    }
                }
                
                $lessonLog = LessonLog::find($request->edit_lesson_log_id);                
                $lessonLog->hours = $request->add_log_hour;
                $lessonLog->lesson_date = $lesson_date;
                $lessonLog->created_by = $request->name;
                $r = $lessonLog->save();

                $username = $lessonLog->user->first_name;

                if($lessonLog->lesson_id)
                {
                    $lesson = Lesson::find($lessonLog->lesson_id);
                    if($lesson)
                    {
                        $temps = json_decode($lesson->lesson_json,true);

                        foreach($temps as $k => $v)
                        {
                            if($k == 'date')
                            {
                                $temps[$k] = $lesson_dateJson;       
                            }
                            elseif ($k == 'trainer') {
                                $temps[$k] = $username;
                            }
                            elseif ($k == 'duration'){
                                $temps[$k] = $request->add_log_hour;
                            }
                        }
                        $lesson->lesson_date = $lesson_date;
                        $lesson->lesson_json = json_encode($temps);
                        $lesson->updated_by = Auth::user()->id;
                        $r = $lesson->save();
                    }  
                }

                $totalHours = DB::table('add_hour_logs')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$lessonLog->student_id)
                       ->sum('hours');

                $finishedHours = DB::table('lesson_hour_logs')
                               ->where('lesson_hour_logs.deleted_at',null)
                               ->where('lesson_hour_logs.student_id',$lessonLog->student_id)
                               ->sum('hours');

                $hoursRemaining = $totalHours - $finishedHours;

                if($hoursRemaining < 0)
                    $hoursRemaining = 0;

                $student = Student::find($lessonLog->student_id);

                if($hoursRemaining == 0)
                {
                    $student->is_past = 1;                    
                }
                $student->remaining_hours = $hoursRemaining;
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
        if($request->ajax()) 
        {            
            $model = LessonLog::find($request->id);
            if(!$model)
            {
                return response()->json(['message' => 'Lesson log Not Found'], 404);   
            }

            $studeId = $model->student_id;

            if($model->lesson_id)
            {
                $model->lesson->delete();
            }

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
    }
}
