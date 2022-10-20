<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Lesson;
use App\Student;
use Auth;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function index(Request $request)
    {
        $lessons = $user = '';
        
        if($request->id)
        {
            $user = Student::find($request->id);
            $lessons = Lesson::where('student_id',$user->id)->where('deleted_at',null)->orderBy('created_at','desc')->get();
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
                                            <input type="text" name="'.$tempName[2].'" placeholder="'.normal_case($tempName[2]).':" value="'.$tempValue[2].'">                                            
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[3]).':</label>
                                            <input type="text" name="'.$tempName[3].'" placeholder="'.normal_case($tempName[3]).':" value="'.$tempValue[3].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[4]).':</label>
                                            <input type="text" name="'.$tempName[4].'" placeholder="'.normal_case($tempName[4]).':" value="'.$tempValue[4].'">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[5]).':</label>
                                            <input type="text" name="'.$tempName[5].'" placeholder="'.normal_case($tempName[5]).':" value="'.$tempValue[5].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[6]).':</label>
                                            <input type="text" name="'.$tempName[6].'" placeholder="'.normal_case($tempName[6]).':" value="'.$tempValue[6].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[7]).':</label>
                                            <input type="text" name="'.$tempName[7].'" placeholder="'.normal_case($tempName[7]).':" value="'.$tempValue[7].'">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[8]).':</label>
                                            <input type="text" name="'.$tempName[8].'" placeholder="'.normal_case($tempName[8]).':" value="'.$tempValue[8].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[9]).':</label>
                                            <input type="text" name="'.$tempName[9].'" placeholder="'.normal_case($tempName[9]).':" value="'.$tempValue[9].'">
                                        </td>
                                        <td>
                                        <label class="font-weight-bold">'.normal_case($tempName[10]).':</label>
                                            <input type="text" name="'.$tempName[10].'" placeholder="'.normal_case($tempName[10]).':" value="'.$tempValue[10].'">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[10]).':</label>
                                            <input type="text" name="'.$tempName[10].'" placeholder="'.normal_case($tempName[10]).':" value="'.$tempValue[10].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[11]).':</label>
                                            <input type="text" name="'.$tempName[11].'" placeholder="'.normal_case($tempName[11]).':" value="'.$tempValue[11].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[12]).':</label>
                                            <input type="text" name="'.$tempName[12].'" placeholder="'.normal_case($tempName[12]).':" value="'.$tempValue[12].'">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[13]).':</label>
                                            <input type="text" name="'.$tempName[13].'" placeholder="'.normal_case($tempName[13]).':" value="'.$tempValue[13].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[14]).':</label>
                                            <input type="text" name="'.$tempName[14].'" placeholder="'.normal_case($tempName[14]).':" value="'.$tempValue[14].'">
                                        </td>
                                        <td>
                                            <label class="font-weight-bold">'.normal_case($tempName[15]).':</label>
                                            <input type="text" name="'.$tempName[15].'" placeholder="'.normal_case($tempName[15]).':" value="'.$tempValue[15].'">
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

        return view('lessons.index',compact('user','lessons','html'));
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

                "trainer" => "",
                "date" => "",
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

                "trainer" => "",
                "date" => "",
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

            
            $lesson->lesson_json = json_encode($temps);
            $lesson->updated_by = Auth::user()->id;
            $r = $lesson->save();

            if($r)
            {            
                toastr()->success('Lesson updated Successfully');
                return redirect()->back();
            }
            else
            {
                toastr()->error('An error has occurred please try again later.');
                return back();
            }

        }
    }
}
