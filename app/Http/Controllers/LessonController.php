<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Lesson;
use App\Student;
use App\LessonLog;
use App\Notification;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function index(Request $request)
    {
        $lessons = $user = '';

        $q = '';
        if(isset($request->q))
            $q = $request->q;
        
        if($request->id)
        {
            $user = Student::find($request->id);
            $lessons = Lesson::where('student_id',$user->id)->where('template_id',1)->where('lesson_json','like','%'.$q.'%')->where('deleted_at',null)->orderBy('lesson_date','desc')->orderBy('id','desc')->get();
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

                $trainer = $value->lessonHourLogs ? ($value->lessonHourLogs->user->first_name ?? $value->user->first_name) : $value->user->first_name;
                $color = $value->lessonHourLogs ? ($value->lessonHourLogs->user->color ?? $value->user->first_name) : $value->user->color;

                if($key % 2 == 0)
                    $html .= '<div class="row">';
                $html .= '<div class="col-md-6">';
                $html .= '<form action="'.route("lesson.update").'" method="POST" class="forms">';
                $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $html .= '<input type="hidden" name="student_id" value="'.$request->id.'" />';
                $html .= '<input type="hidden" name="update_id" value="'.$value->id.'" />';
                $html .= '<input type="hidden" name="duplicate" class="duplicate" value="0" />';
                $html .= '<div class="lesson-table pr-lg-2" id="sift'.$value->id.'">';
                $html .= '<div class="save-btn">';
                $html .= '<button type="submit" class="orange-bg"><img src="'.asset('images/download2.png').'" height="20"> Save</button>';
                $html .= '<a href="javascript:void(0)" data-del-id="'.$value->id.'" class="del-lesson"><img src="'.asset('images/delete-button.svg').'" alt="" class="del-les-img"></a>';
                $html .= '</div>';
                $html .= '<table>
                            <tr>
                                <td>
                                <label class="font-weight-bold">'.normal_case($tempName[0]).':</label>
                                    <input type="text" class="datepicker1" id="'.$tempName[0].'_'.$value->id.'" name="'.$tempName[0].'" placeholder="'.normal_case($tempName[0]).':" value="'.$tempValue[0].'">
                                   <span>
                                        <label class="font-weight-bold">'.normal_case($tempName[1]).':</label>
                                        <input type="text" name="'.$tempName[1].'" placeholder="'.normal_case($tempName[1]).':" value="'.$trainer.'" style="background: '.$color.'" readonly>
                                   </span>
                                    <label class="font-weight-bold">'.normal_case($tempName[2]).':</label>
                                    <input type="number" step="0.25" name="'.$tempName[2].'" placeholder="'.normal_case($tempName[2]).':" value="'.$tempValue[2].'" required>                                            
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[3]).':</label>
                                    <textarea name="'.$tempName[3].'" placeholder="'.normal_case($tempName[3]).'" rows="10" cols="5" class="vestibular">'.$tempValue[3].'</textarea>
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[4]).':</label>
                                    <textarea name="'.$tempName[4].'" placeholder="'.normal_case($tempName[4]).'" rows="10" cols="5" class="proprioception">'.$tempValue[4].'</textarea>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[5]).':</label>
                                    <textarea name="'.$tempName[5].'" placeholder="'.normal_case($tempName[5]).'" rows="10" cols="5" class="muscle_tone">'.$tempValue[5].'</textarea>
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[6]).':</label>
                                    <textarea name="'.$tempName[6].'" placeholder="'.normal_case($tempName[6]).'" rows="10" cols="5" class="reflex">'.$tempValue[6].'</textarea>
                                    
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[7]).':</label>
                                    <textarea name="'.$tempName[7].'" placeholder="'.normal_case($tempName[7]).'" rows="10" cols="5" class="kinestesia">'.$tempValue[7].'</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[8]).':</label>
                                    <textarea name="'.$tempName[8].'" placeholder="'.normal_case($tempName[8]).'" rows="5" cols="5" class="massage">'.$tempValue[8].'</textarea>
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[9]).':</label>
                                    <textarea name="'.$tempName[9].'" placeholder="'.normal_case($tempName[9]).'" rows="5" cols="5" class="tactile">'.$tempValue[9].'</textarea>
                                </td>
                                <td>
                                <label class="font-weight-bold">'.normal_case($tempName[10]).':</label>
                                    <textarea name="'.$tempName[10].'" placeholder="'.normal_case($tempName[10]).'" rows="5" cols="5" class="emotions">'.$tempValue[10].'</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[11]).':</label>
                                    <textarea name="'.$tempName[11].'" placeholder="'.normal_case($tempName[11]).'" rows="5" cols="5" class="vp">'.$tempValue[11].'</textarea>
                                    
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[12]).':</label>
                                    <textarea name="'.$tempName[12].'" placeholder="'.normal_case($tempName[12]).'" rows="5" cols="5" class="ep">'.$tempValue[12].'</textarea>
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[13]).':</label>
                                    <textarea name="'.$tempName[13].'" placeholder="'.normal_case($tempName[13]).'" rows="5" cols="5" class="others">'.$tempValue[13].'</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="3" colspan="3">
                                    <label class="font-weight-bold">'.normal_case($tempName[14]).':</label>
                                    <textarea name="'.$tempName[14].'" placeholder="'.normal_case($tempName[14]).'" rows="5" cols="5" class="ft">'.$tempValue[14].'</textarea>
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

    public function btIndex(Request $request)
    {
        $lessons = $user = '';

        $q = '';
        if(isset($request->q))
            $q = $request->q;
        
        if($request->id)
        {
            $user = Student::find($request->id);
            $lessons = Lesson::where('student_id',$user->id)->where('template_id',2)->where('lesson_json','like','%'.$q.'%')->where('deleted_at',null)->orderBy('lesson_date','desc')->orderBy('id','desc')->get();
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

                $trainer = $value->lessonHourLogs ? ($value->lessonHourLogs->user->first_name ?? $value->user->first_name) : $value->user->first_name;    
                $color = $value->lessonHourLogs ? ($value->lessonHourLogs->user->color ?? $value->user->first_name) : $value->user->color;      

                if($key % 2 == 0)
                    $html .= '<div class="row">';
                $html .= '<div class="col-md-6">';
                $html .= '<form action="'.route("lesson-bt.update").'" method="POST" class="forms">';
                $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $html .= '<input type="hidden" name="student_id" value="'.$request->id.'" />';
                $html .= '<input type="hidden" name="update_id" value="'.$value->id.'" />';
                $html .= '<input type="hidden" name="duplicate" class="duplicate" value="0" />';
                $html .= '<div class="lesson-table pl-lg-2" id="btlang'.$value->id.'">';
                $html .= '<div class="save-btn">';
                $html .= '<button type="submit" class="orange-bg"><img src="'.asset('images/download2.png').'" height="20"> Save</button>';
                $html .= '<a href="javascript:void(0)" data-del-id="'.$value->id.'" class="del-lesson"><img src="'.asset('images/delete-button.svg').'" alt="" class="del-les-img"></a>';
                $html .= '</div>';
                $html .= '<table class="bt-lang">
                            <tr>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[0]).':</label>
                                    <input type="text" class="datepicker1" id="'.$tempName[0].'_'.$value->id.'" name="'.$tempName[0].'" placeholder="'.normal_case($tempName[0]).':" value="'.$tempValue[0].'">                                    
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[1]).':</label>
                                    <input type="text" name="'.$tempName[1].'" placeholder="'.normal_case($tempName[1]).':" value="'.$trainer.'" style="background: '.$color.'" readonly>
                                </td>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[2]).':</label>
                                    <input type="number" step="0.25" name="'.$tempName[2].'" placeholder="'.normal_case($tempName[2]).':" value="'.$tempValue[2].'" required>  
                                    
                                </td>
                            </tr>
                            </table>
                            <table class="bt-lang">
                            <tr class="">                                
                                <td class="third-col" rowspan="6">
                                    <label class="font-weight-bold">'.normal_case($tempName[3]).':</label>
                                    <textarea name="'.$tempName[3].'" placeholder="'.normal_case($tempName[3]).'" class="ckeditor">'.$tempValue[3].'</textarea>
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

        return view('lessons.bt-index',compact('user','lessons','html','q'));
    }

    public function imIndex(Request $request)
    {
        $lessons = $user = '';

        $q = '';
        if(isset($request->q))
            $q = $request->q;
        
        if($request->id)
        {
            $user = Student::find($request->id);
            $lessons = Lesson::where('student_id',$user->id)->where('template_id',3)->where('lesson_json','like','%'.$q.'%')->where('deleted_at',null)->orderBy('lesson_date','desc')->orderBy('id','desc')->get();
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
                $html .= '<form action="'.route("lesson-im.update").'" method="POST">';
                $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $html .= '<input type="hidden" name="student_id" value="'.$request->id.'" />';
                $html .= '<input type="hidden" name="update_id" value="'.$value->id.'" />';
                $html .= '<div class="lesson-table pl-lg-2">';
                $html .= '<div class="save-btn">';
                $html .= '<button type="submit" class="orange-bg"><img src="'.asset('images/download2.png').'" height="20"> Save</button>';
                $html .= '<a href="javascript:void(0)" data-del-id="'.$value->id.'" class="del-lesson"><img src="'.asset('images/delete-button.svg').'" alt="" class="del-les-img"></a>';
                $html .= '</div>';
                $html .= '<table class="im">
                            <tr>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[0]).':</label>
                                    <input type="text" class="datepicker1" id="'.$tempName[0].'_'.$value->id.'" name="'.$tempName[0].'" placeholder="'.normal_case($tempName[0]).':" value="'.$tempValue[0].'">                                    
                                </td>
                            </tr>
                            </table>
                            <table class="im">
                            <tr class="bt-lang bt-none">
                                <td>Activity</td>
                                <td>Duration</td>
                                <td>Task Avg</td>
                                <td>Var Avg</td>
                                <td>SRO(%)</td>
                                <td>E</td>
                                <td>L</td>
                                <td>HighestIAR</td>
                                <td>Burst</td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" name="'.$tempName[1].'" value="'.$tempValue[1].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[2].'" value="'.$tempValue[2].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[3].'" value="'.$tempValue[3].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[4].'" value="'.$tempValue[4].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[5].'" value="'.$tempValue[5].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[6].'" value="'.$tempValue[6].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[7].'" value="'.$tempValue[7].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[8].'" value="'.$tempValue[8].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[9].'" value="'.$tempValue[9].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" name="'.$tempName[10].'" value="'.$tempValue[10].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[11].'" value="'.$tempValue[11].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[12].'" value="'.$tempValue[12].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[13].'" value="'.$tempValue[13].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[14].'" value="'.$tempValue[14].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[15].'" value="'.$tempValue[15].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[16].'" value="'.$tempValue[16].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[17].'" value="'.$tempValue[17].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[18].'" value="'.$tempValue[18].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" name="'.$tempName[19].'" value="'.$tempValue[19].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[20].'" value="'.$tempValue[20].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[21].'" value="'.$tempValue[21].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[22].'" value="'.$tempValue[22].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[23].'" value="'.$tempValue[23].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[24].'" value="'.$tempValue[24].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[25].'" value="'.$tempValue[25].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[26].'" value="'.$tempValue[26].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[27].'" value="'.$tempValue[27].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" name="'.$tempName[28].'" value="'.$tempValue[28].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[29].'" value="'.$tempValue[29].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[30].'" value="'.$tempValue[30].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[31].'" value="'.$tempValue[31].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[32].'" value="'.$tempValue[32].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[33].'" value="'.$tempValue[33].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[34].'" value="'.$tempValue[34].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[35].'" value="'.$tempValue[35].'">
                                </td>
                                <td>
                                    <input type="text" name="'.$tempName[36].'" value="'.$tempValue[36].'">
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

        return view('lessons.im-index',compact('user','lessons','html','q'));
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
        $lesson->lesson_date = Carbon::now()->format('Y-m-d');
        $lesson->lesson_json = $this->getLessonJson($request->id);
        $lesson->updated_by = $user->id;
        $r = $lesson->save();

        if($r)
        {            
            session(['lessonMsg' => 'Template added Successfully']);
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

                "date" => Carbon::now()->format('d-m-Y'),
                "trainer" => Auth::user()->first_name,
                "duration" => "",
                // "objective_of_lesson" => "",
                "vestibular" => "",
                "proprioception" => "",
                "muscle_tone" => "",
                "reflex" => "",
                "kinestesia" => "",
                "massage" => "",
                "tactile" => "",
                "emotions" => "",
                "vp" => "",
                // "oral" => "",
                "ep" => "",
                "others" => "",
                "ft" => "",
                // "parent_feedback" => "",
            ];
        }
        else if($id == 2)
        {
            $template = [

                "date" => Carbon::now()->format('d-m-Y'),
                "trainer" => Auth::user()->first_name,
                "duration" => "",
                "description" => "",                
            ];
        }
        else{
            $template = [

                "date" => Carbon::now()->format('d-m-Y'),
                "activity1" => "",
                "duration1" => "",
                "taskavg1" => "",
                "varavg1" => "",
                "sro1" => "",
                "e1" => "",
                "l1" => "",
                "highestiar1" => "",
                "burst1" => "",
                "activity2" => "",
                "duration2" => "",
                "taskavg2" => "",
                "varavg2" => "",
                "sro2" => "",
                "e2" => "",
                "l2" => "",
                "highestiar2" => "",
                "burst2" => "",
                "activity3" => "",
                "duration3" => "",
                "taskavg3" => "",
                "varavg3" => "",
                "sro3" => "",
                "e3" => "",
                "l3" => "",
                "highestiar3" => "",
                "burst3" => "",
                "activity4" => "",
                "duration4" => "",
                "taskavg4" => "",
                "varavg4" => "",
                "sro4" => "",
                "e4" => "",
                "l4" => "",
                "highestiar4" => "",
                "burst4" => "",
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

            // Convert date to db date formate
            $lesson_date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

            $lessonDup = Lesson::where('id','!=',$request->update_id)->where('template_id',$lesson->template_id)->where('lesson_date',$lesson_date)->where('deleted_at',null)->first();

            if(!$request->duplicate)
            {                    
                if($lessonDup)
                {
                    $result = ['status' => true, 'match' => 1, 'data' => []];
                    return response()->json($result);
                }
            }
            
            $temps = json_decode($lesson->lesson_json,true);
            
            foreach($temps as $k => $v)
            {                
                foreach ($data as $key => $value) {
                    if($k == $key)
                    {
                        $temps[$k] = $value;
                        if($value)
                        {
                            $notIds = [];                
                            $str = $value;
                            $count = 0;
                            $strCount = substr_count($str, 'href="');                

                            while($strCount > 0)
                            {                    
                                $temp = preg_match('~href="\K\d+~', $str, $out) ? $out[0] : '';
                                $notIds[$count] = $temp;
                                
                                $tempWord = 'href="'.$temp.'"';
                                
                                $str = str_ireplace($tempWord, 'asd', $str);
                                
                                $strCount--;
                                $count++;
                            }

                            if(count($notIds))
                            {
                                foreach($notIds as $uId)
                                {                        
                                    $notification = Notification::where('student_id',$request->student_id)->where('user_id',$uId)->where('case_id',$request->update_id)->where('case_type',4)->where('updated_by',Auth::user()->id)->where('deleted_at',null)->first();

                                    if($notification)
                                        continue;                        

                                    $notification = new Notification;
                                    $notification->student_id = $request->student_id;
                                    $notification->user_id = $uId;
                                    $notification->case_id = $request->update_id;
                                    $notification->case_type = 4; // 1 : Case Management Meeting, 2: Parent Review Session, 3: comments, 4: SI/FT
                                    $notification->updated_by = Auth::user()->id;
                                    $notification->save();
                                }
                            }
                        }                            
                    }
                    if($key == 'date')
                    {                                            
                        $dt = $value;
                    }                 
                }
            }            
            
            $lesson->lesson_date = Carbon::parse($dt)->format('Y-m-d');
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
            $lessonLog->hours = $request->duration;
            $lessonLog->lesson_date = Carbon::parse($dt)->format('Y-m-d');
            $lessonLog->created_by = $lessonLog->created_by ?? Auth::user()->id;
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
                // session(['lessonUpdated' => 'form saved Successfully']);
                // return redirect()->route('lesson',$request->student_id);
                $result = ['status' => true, 'message' => 'form saved Successfully', 'data' => []];
                return response()->json($result);
            }
            else
            {
                // toastr()->error('An error has occurred please try again later.');
                // return redirect()->route('lesson',$request->student_id);
                $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
                return response()->json($result);
                
            }

        }
    }

    public function btUpdate(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();
            
        if(isset($request->student_id) && isset($request->update_id))
        {
            $lesson = Lesson::find($request->update_id);

            // Convert date to db date formate
            $lesson_date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

            $lessonDup = Lesson::where('id','!=',$request->update_id)->where('template_id',$lesson->template_id)->where('lesson_date',$lesson_date)->where('deleted_at',null)->first();

            if(!$request->duplicate)
            {                    
                if($lessonDup)
                {
                    $result = ['status' => true, 'match' => 1, 'data' => []];
                    return response()->json($result);
                }
            }
            
            $temps = json_decode($lesson->lesson_json,true);
            
            foreach($temps as $k => $v)
            {                
                foreach ($data as $key => $value) {
                    if($k == $key)
                    {
                        $temps[$k] = $value;
                        if($value)
                        {
                            $notIds = [];                
                            $str = $value;
                            $count = 0;
                            $strCount = substr_count($str, 'href="');                

                            while($strCount > 0)
                            {                    
                                $temp = preg_match('~href="\K\d+~', $str, $out) ? $out[0] : '';
                                $notIds[$count] = $temp;
                                
                                $tempWord = 'href="'.$temp.'"';
                                
                                $str = str_ireplace($tempWord, 'asd', $str);
                                
                                $strCount--;
                                $count++;
                            }

                            if(count($notIds))
                            {
                                foreach($notIds as $uId)
                                {                        
                                    $notification = Notification::where('student_id',$request->student_id)->where('user_id',$uId)->where('case_id',$request->update_id)->where('case_type',5)->where('updated_by',Auth::user()->id)->where('deleted_at',null)->first();

                                    if($notification)
                                        continue;                        

                                    $notification = new Notification;
                                    $notification->student_id = $request->student_id;
                                    $notification->user_id = $uId;
                                    $notification->case_id = $request->update_id;
                                    $notification->case_type = 5; // 1 : Case Management Meeting, 2: Parent Review Session, 3: comments, 4: SI/FT, 5: BT/Lang
                                    $notification->updated_by = Auth::user()->id;
                                    $notification->save();
                                }
                            }
                        }                        
                    }
                    if($key == 'date')
                    {
                        $dt = $value;
                    }                 
                }
            }
            
            $lesson->lesson_date = Carbon::parse($dt)->format('Y-m-d');
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
            $lessonLog->hours = $request->duration;
            $lessonLog->lesson_date = Carbon::parse($dt)->format('Y-m-d');
            $lessonLog->created_by = $lessonLog->created_by ?? Auth::user()->id;
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
                // session(['lessonUpdated' => 'form saved Successfully']);
                // return redirect()->route('lesson-bt',$request->student_id);
                $result = ['status' => true, 'message' => 'form saved Successfully', 'data' => []];
                return response()->json($result);
            }
            else
            {
                // toastr()->error('An error has occurred please try again later.');
                // return redirect()->route('lesson-bt',$request->student_id);
                $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
                return response()->json($result);
                // return back();
            }

        }
    }

    public function imUpdate(Request $request)
    {
        // $this->validator($request->all())->validate();

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
                    if($key == 'date')
                    {
                        $dt = $value;
                    }                 
                }
            }
            
            $lesson->lesson_date = Carbon::parse($dt)->format('Y-m-d');
            $lesson->lesson_json = json_encode($temps);
            $lesson->updated_by = Auth::user()->id;
            $r = $lesson->save();

            // lesson log entry : starts
            // $lessonLog = LessonLog::where('student_id',$request->student_id)->where('lesson_id',$request->update_id)->where('deleted_at',null)->first();
            // if(!$lessonLog)
            // {
            //     $lessonLog = new LessonLog;
            // }
            // $lessonLog->student_id = $request->student_id;
            // $lessonLog->hours = $request->duration;
            // $lessonLog->lesson_date = Carbon::parse($dt)->format('Y-m-d');
            // $lessonLog->created_by = Auth::user()->id;
            // $lessonLog->lesson_id = $lesson->id;
            // $lessonLog->save();
            // $rl = $lessonLog->save();

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
            
            if($r)
            {            
                session(['lessonUpdated' => 'form saved Successfully']);
                return redirect()->route('lesson-im',$request->student_id);
            }
            else
            {
                toastr()->error('An error has occurred please try again later.');
                return redirect()->route('lesson-im',$request->student_id);
                // return back();
            }

        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'duration' => ['required', 'numeric'],                        
        ]);
    }

    public function delete(Request $request)
    {
        $model = Lesson::find($request->id);                
        $model->updated_by = Auth::user()->id;
        $model->deleted_at = Carbon::now();

        // udpate related lesson hours log
        $lg = LessonLog::where('lesson_id',$request->id)->update(['lesson_id' => null]);        

        if($model->save()){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
    }
}
