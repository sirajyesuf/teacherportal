<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LessonService;
use App\LessonLog;
use App\Lesson;
use Validator;
use Auth;
use DB;
use App\Student;
use Carbon\Carbon;

class LogHourController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }
    
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

                $lessonLog = LessonLog::where('lesson_date',$lesson_date)->where('student_id',$request->add_log_student_id)->where('program', $request->program)->where('deleted_at',null)->first();

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
                $lessonLog->program = $request->program;
                $lessonLog->created_by = $request->name;
                $r = $lessonLog->save();

                // $studentId = $request->add_log_student_id;

                // $addedHours = DB::table('add_hour_logs')
                //     ->where('deleted_at', null)
                //     ->where('student_id', $studentId)
                //     ->orderBy('created_at')
                //     ->pluck('hours');

                // // Get all the hours completed by the student in ascending order of creation time (assuming created_at is used to determine order)
                // $completedHours = DB::table('lesson_hour_logs')
                //     ->where('deleted_at', null)
                //     ->where('student_id', $studentId)
                //     ->orderBy('created_at')
                //     ->pluck('hours');

                // // Initialize variables to keep track of remaining and completed hours
                // $remainingHours = 0;
                // $completedTotalHours = 0;

                // // Loop through the added hours to calculate the remaining and completed hours in batches
                // foreach ($addedHours as $addedHour) {
                //     // Calculate the remaining hours for the current batch
                //     $remainingInBatch = max(0, $addedHour - $completedTotalHours);

                //     // Add the remaining hours of the batch to the total remaining hours
                //     $remainingHours += $remainingInBatch;

                //     // Add the completed hours of the batch to the total completed hours
                //     $completedTotalHours += ($addedHour - $remainingInBatch);

                //     // Check if there are completed hours for the current batch
                //     if (count($completedHours) > 0) {
                //         // Get the first completed hour from the array and remove it
                //         $completedHour = array_shift($completedHours);

                //         // Deduct the completed hours from the current batch
                //         $completedTotalHours -= $completedHour;
                //     }
                // }

                // // Make the student a past student if all hours are completed in all batches
                // $isPastStudent = ($remainingHours === 0) ? 1 : 0;

                // // Update the student record with the remaining hours and past student status
                // $student = Student::find($studentId);
                // $student->remaining_hours = $remainingHours;
                // $student->is_past = $isPastStudent;
                // $student->save();

                // $totalHours = DB::table('add_hour_logs')
                //        ->where('add_hour_logs.deleted_at',null)
                //        ->where('add_hour_logs.student_id',$request->add_log_student_id)
                //        ->sum('hours');

                // $finishedHours = DB::table('lesson_hour_logs')
                //                ->where('lesson_hour_logs.deleted_at',null)
                //                ->where('lesson_hour_logs.student_id',$request->add_log_student_id)
                //                ->sum('hours');

                // $hoursRemaining = $totalHours - $finishedHours;

                // if($hoursRemaining < 0)
                //     $hoursRemaining = 0;

                // old code to count remaining hour
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
                // old code to count remaining hour ends


                // new code
                // $lessonService = new LessonService;
                // $hoursRemaining = $lessonService->getRemainingHour($request->add_log_student_id);
                // new code ends

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
                $lessonLog->program = $request->program;
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

                // old code to count remaining hour
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
                // old code to count remaining hour ends

                // new code
                // $lessonService = new LessonService;
                // $hoursRemaining = $lessonService->getRemainingHour($lessonLog->student_id);
                // new code ends

                $student = Student::find($lessonLog->student_id);
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
                if(isset($model->lesson->id))
                {
                    $model->lesson->delete();
                }
            }

            $model->delete();
            
            // old code to count remaining hour
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
            // old code to count remaining hour ends

            // new code
            // $lessonService = new LessonService;
            // $hoursRemaining = $lessonService->getRemainingHour($studeId);
            // new code ends
            
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
