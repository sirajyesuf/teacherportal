<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LessonLog;
use Validator;
use Auth;

class LogHourController extends Controller
{
    public function add(Request $request)
    {
        if($request->ajax()) 
        {
            $rules = array(
                'add_log_hour'=>'required|numeric',                    
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

                $lessonLog = new LessonLog;
                $lessonLog->student_id = $request->add_log_hours_id;
                $lessonLog->hours = $request->add_log_hour;
                $lessonLog->created_by = Auth::user()->id;
                $r = $lessonLog->save();

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
}
