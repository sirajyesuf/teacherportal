<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Lesson;
use App\Student;
use App\LessonLog;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function index(Request $request)
    {
        $lessons = $user = '';

        \Log::info($request->all());
        $q = '';
        if(isset($request->q))
            $q = $request->q;
        
        if($request->id)
        {
            $user = Student::find($request->id);
            $lessons = Lesson::where('student_id',$user->id)->where('lesson_json','like','%'.$q.'%')->where('deleted_at',null)->orderBy('created_at','desc')->get();
        }

        if($lessons)
        {
            $html = '';
            foreach ($lessons as $key => $value) {
                $arrays = json_decode($value->lesson_json);
                $tempName = $tempValue = [];

                $count = 0;
                foreach($arrays as $key1 => $value1)
                {
                    $tempName[$count] = $key1;
                    $tempValue[$count] = $value1;
                    $count++;
                }                

                if($key % 2 == 0)
                    $html .= '<div class="row">';
                $html .= '<div class="col-md-6">';
                $html .= '<form action="'.route("lesson.update").'" method="POST">';
                $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $html .= '<input type="hidden" name="student_id" value="'.$request->id.'" />';
                $html .= '<input type="hidden" name="update_id" value="'.$value->id.'" />';
                $html .= '<div class="lesson-table pr-xl-4 pr-lg-2">';
                $html .= '<div class="save-btn">';
                $html .= '<button type="submit"><img src="'.asset('images/download.svg').'" alt=""> Save</button>';
                $html .= '</div>';
                $html .= '<table>
                                    <tr>
                                        <td>
                                        <label class="font-weight-bold">'.normal_case($tempName[0]).':</label>
                                        <input type="text" name="'.$tempName[0].'" placeholder="'.normal_case($tempName[0]).':" value="'.$tempValue[0].'">
                                        
                                           <span>
                                            <label class="font-weight-bold">'.normal_case($tempName[1]).':</label>
                                            <input type="text" class="datepicker1" id="'.$tempName[1].'_'.$value->id.'" name="'.$tempName[1].'" placeholder="'.normal_case($tempName[1]).':" value="'.$tempValue[1].'">
                                           </span>
                                            <label class="font-weight-bold">'.normal_case($tempName[2]).':</label>
                                            <input type="number" step="0.25" name="'.$tempName[2].'" placeholder="'.normal_case($tempName[2]).':" value="'.$tempValue[2].'" required>                                            
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[3]).':</label>
                                            <textarea name="'.$tempName[3].'" placeholder="'.normal_case($tempName[3]).'" rows="7" cols="5">'.$tempValue[3].'</textarea>
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[4]).':</label>
                                            <textarea name="'.$tempName[4].'" placeholder="'.normal_case($tempName[4]).'" rows="7" cols="5">'.$tempValue[4].'</textarea>
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[5]).':</label>
                                            <textarea name="'.$tempName[5].'" placeholder="'.normal_case($tempName[5]).'" rows="5" cols="5">'.$tempValue[5].'</textarea>
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[6]).':</label>
                                            <textarea name="'.$tempName[6].'" placeholder="'.normal_case($tempName[6]).'" rows="5" cols="5">'.$tempValue[6].'</textarea>
                                            
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[7]).':</label>
                                            <textarea name="'.$tempName[7].'" placeholder="'.normal_case($tempName[7]).'" rows="5" cols="5">'.$tempValue[7].'</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[8]).':</label>
                                            <textarea name="'.$tempName[8].'" placeholder="'.normal_case($tempName[8]).'" rows="5" cols="5">'.$tempValue[8].'</textarea>
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[9]).':</label>
                                            <textarea name="'.$tempName[9].'" placeholder="'.normal_case($tempName[9]).'" rows="5" cols="5">'.$tempValue[9].'</textarea>
                                        </td>
                                        <td>
                                        <label class="font-weight-bold">'.normal_case($tempName[10]).':</label>
                                            <textarea name="'.$tempName[10].'" placeholder="'.normal_case($tempName[10]).'" rows="5" cols="5">'.$tempValue[10].'</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[11]).':</label>
                                            <textarea name="'.$tempName[11].'" placeholder="'.normal_case($tempName[11]).'" rows="5" cols="5">'.$tempValue[11].'</textarea>
                                            
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[12]).':</label>
                                            <textarea name="'.$tempName[12].'" placeholder="'.normal_case($tempName[12]).'" rows="5" cols="5">'.$tempValue[12].'</textarea>
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[13]).':</label>
                                            <textarea name="'.$tempName[13].'" placeholder="'.normal_case($tempName[13]).'" rows="5" cols="5">'.$tempValue[13].'</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[14]).':</label>
                                            <textarea name="'.$tempName[14].'" placeholder="'.normal_case($tempName[14]).'" rows="5" cols="5">'.$tempValue[14].'</textarea>
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[15]).':</label>
                                            <textarea name="'.$tempName[15].'" placeholder="'.normal_case($tempName[15]).'" rows="5" cols="5">'.$tempValue[15].'</textarea>
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[16]).':</label>
                                            <textarea name="'.$tempName[16].'" placeholder="'.normal_case($tempName[16]).'" rows="5" cols="5">'.$tempValue[16].'</textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </form>';
                if($key % 2 == 1)
                    $html .= '</div>';


            }
        }

        return view('lessons.index',compact('user','lessons','html','q'));
    }

    public function templateChoice(Student $student)
    {        
        return view('lessons.lesson-template',compact('student'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $lesson = new Lesson;
        $lesson->student_id = $request->student_id;
        $lesson->template_id = $request->id;
        $lesson->lesson_json = $this->getLessonJson($request->id);
        $lesson->updated_by = $user->id;
        $r = $lesson->save();

        if($r)
        {            
            $result = ['status' => true, 'message' => '', 'data' => []];
            return response()->json($result);
        }
        else
        {
            $result = ['status' => false, 'message' => 'Error', 'data' => []];
            return response()->json($result);
        }
    }

    public function getLessonJson($id)
    {
        if($id == 1)
        {            
            $template = [

                "trainer" => Auth::user()->name,
                "date" => Carbon::now()->format('d-m-Y'),
                "lesson_length" => "",
                "objective_of_lesson" => "",
                "message" => "",
                "reflex" => "",
                "tactile" => "",
                "vestibular" => "",
                "oral" => "",
                "kinestesia" => "",
                "muscle_tone" => "",
                "proprioception" => "",
                "vision" => "",
                "emotions" => "",
                "others" => "",
                "plan_for_next_session" => "",
                "parent_feedback" => "",
            ];
        }
        else
        {
            $template = [

                "trainer" => Auth::user()->name,
                "date" => Carbon::now()->format('d-m-Y'),
                "lesson_length" => "",
                "objective_of_lesson" => "",
                "message" => "",
                "reflex" => "",
                "tactile" => "",
                "vestibular" => "",
                "oral" => "",
                "kinestesia" => "",
                "muscle_tone" => "",
                "proprioception" => "",
                "vision" => "",
                "emotions" => "",
                "others" => "",
                "plan_for_next_session" => "",
                "parent_feedback" => "",
            ];
        }

        return json_encode($template);
    }

    public function update(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();
            
        if(isset($request->student_id) && isset($request->update_id))
        {
            $lesson = Lesson::find($request->update_id);
            
            $temps = json_decode($lesson->lesson_json,true);
            
            foreach($temps as $k => $v)
            {                
                foreach ($data as $key => $value) {
                    if($k == $key)
                    {
                        $temps[$k] = $value;                        
                    }                    
                }
            }            
            \Log::info('as');
            
            $lesson->lesson_json = json_encode($temps);
            $lesson->updated_by = Auth::user()->id;
            $r = $lesson->save();

            // lesson log entry : starts
            $lessonLog = LessonLog::where('student_id',$request->student_id)->where('lesson_id',$request->update_id)->where('deleted_at',null)->first();
            if(!$lessonLog)
            {
                $lessonLog = new LessonLog;
            }
            $lessonLog->student_id = $request->student_id;
            $lessonLog->hours = $request->lesson_length;
            $lessonLog->created_by = Auth::user()->id;
            $lessonLog->lesson_id = $lesson->id;
            $lessonLog->save();
            $rl = $lessonLog->save();

            $totalHours = DB::table('add_hour_logs')
                   ->where('add_hour_logs.deleted_at',null)
                   ->where('add_hour_logs.student_id',$request->student_id)
                   ->sum('hours');

            $finishedHours = DB::table('lesson_hour_logs')
                           ->where('lesson_hour_logs.deleted_at',null)
                           ->where('lesson_hour_logs.student_id',$request->student_id)
                           ->sum('hours');

            $hoursRemaining = $totalHours - $finishedHours;

            if($hoursRemaining < 0)
                $hoursRemaining = 0;

            $student = Student::find($request->student_id);

            if($hoursRemaining == 0)
            {
                $student->is_past = 1;                    
            }
            $student->remaining_hours = $hoursRemaining;
            $student->save();

            // lesson log entry : Ends
            
            if($rl)
            {            
                return redirect()->route('lesson',$request->student_id);
            }
            else
            {
                toastr()->error('An error has occurred please try again later.');
                return redirect()->route('lesson',$request->student_id);
                // return back();
            }

        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'lesson_length' => ['required', 'numeric'],                        
        ]);
    }
}
