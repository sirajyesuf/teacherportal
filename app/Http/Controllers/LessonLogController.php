<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\LogHour;
use Auth;

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
