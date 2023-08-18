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
                                    <textarea name="'.$tempName[3].'" placeholder="'.normal_case($tempName[3]).'" class="">'.$tempValue[3].'</textarea>
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

    public function sandIndex(Request $request)
    {
        $lessons = $user = '';

        $q = '';
        if(isset($request->q))
            $q = $request->q;
        
        if($request->id)
        {
            $user = Student::find($request->id);
            $lessons = Lesson::where('student_id',$user->id)->where('template_id',4)->where('lesson_json','like','%'.$q.'%')->where('deleted_at',null)->orderBy('lesson_date','desc')->orderBy('id','desc')->get();
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
                $html .= '<form action="'.route("lesson-sand.update").'" method="POST" class="forms">';
                $html .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $html .= '<input type="hidden" name="student_id" value="'.$request->id.'" />';
                $html .= '<input type="hidden" name="update_id" value="'.$value->id.'" />';
                $html .= '<input type="hidden" name="duplicate" class="duplicate" value="0" />';
                $html .= '<div class="lesson-table pl-lg-2" id="sand'.$value->id.'">';
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
                            </tr>
                            </table>
                            <table class="bt-lang">
                            <tr class="">                                
                                <td class="third-col" rowspan="6">
                                    <label class="font-weight-bold">'.normal_case($tempName[2]).':</label>
                                    <textarea name="'.$tempName[2].'" placeholder="'.normal_case($tempName[2]).'" class="">'.$tempValue[2].'</textarea>
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

        return view('lessons.sand-index',compact('user','lessons','html','q'));
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
                $html .= '<div class="lesson-table pl-lg-2" id="im'.$value->id.'">';
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
                            <tr>
                                <td>
                                    <label class="font-weight-bold">'.normal_case($tempName[1]).':</label>
                                    <input type="text" class="" id="'.$tempName[1].'_'.$value->id.'" name="'.$tempName[1].'" placeholder="'.normal_case($tempName[1]).':" value="'.$tempValue[1].'">                                    
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
                                    <input type="text" class="truncated-text" title="'.$tempValue[2].'" name="'.$tempName[2].'" value="'.$tempValue[2].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[3].'" name="'.$tempName[3].'" value="'.$tempValue[3].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[4].'" name="'.$tempName[4].'" value="'.$tempValue[4].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[5].'" name="'.$tempName[5].'" value="'.$tempValue[5].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[6].'" name="'.$tempName[6].'" value="'.$tempValue[6].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[7].'" name="'.$tempName[7].'" value="'.$tempValue[7].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[8].'" name="'.$tempName[8].'" value="'.$tempValue[8].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[9].'" name="'.$tempName[9].'" value="'.$tempValue[9].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[10].'" name="'.$tempName[10].'" value="'.$tempValue[10].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[11].'" name="'.$tempName[11].'" value="'.$tempValue[11].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[12].'" name="'.$tempName[12].'" value="'.$tempValue[12].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[13].'" name="'.$tempName[13].'" value="'.$tempValue[13].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[14].'" name="'.$tempName[14].'" value="'.$tempValue[14].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[15].'" name="'.$tempName[15].'" value="'.$tempValue[15].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[16].'" name="'.$tempName[16].'" value="'.$tempValue[16].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[17].'" name="'.$tempName[17].'" value="'.$tempValue[17].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[18].'" name="'.$tempName[18].'" value="'.$tempValue[18].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[19].'" name="'.$tempName[19].'" value="'.$tempValue[19].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[20].'" name="'.$tempName[20].'" value="'.$tempValue[20].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[21].'" name="'.$tempName[21].'" value="'.$tempValue[21].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[22].'" name="'.$tempName[22].'" value="'.$tempValue[22].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[23].'" name="'.$tempName[23].'" value="'.$tempValue[23].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[24].'" name="'.$tempName[24].'" value="'.$tempValue[24].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[25].'" name="'.$tempName[25].'" value="'.$tempValue[25].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[26].'" name="'.$tempName[26].'" value="'.$tempValue[26].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[27].'" name="'.$tempName[27].'" value="'.$tempValue[27].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[28].'" name="'.$tempName[28].'" value="'.$tempValue[28].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[29].'" name="'.$tempName[29].'" value="'.$tempValue[29].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[30].'" name="'.$tempName[30].'" value="'.$tempValue[30].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[31].'" name="'.$tempName[31].'" value="'.$tempValue[31].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[32].'" name="'.$tempName[32].'" value="'.$tempValue[32].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[33].'" name="'.$tempName[33].'" value="'.$tempValue[33].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[34].'" name="'.$tempName[34].'" value="'.$tempValue[34].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[35].'" name="'.$tempName[35].'" value="'.$tempValue[35].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[36].'" name="'.$tempName[36].'" value="'.$tempValue[36].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[37].'" name="'.$tempName[37].'" value="'.$tempValue[37].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[38].'" name="'.$tempName[38].'" value="'.$tempValue[38].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[39].'" name="'.$tempName[39].'" value="'.$tempValue[39].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[40].'" name="'.$tempName[40].'" value="'.$tempValue[40].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[41].'" name="'.$tempName[41].'" value="'.$tempValue[41].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[42].'" name="'.$tempName[42].'" value="'.$tempValue[42].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[43].'" name="'.$tempName[43].'" value="'.$tempValue[43].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[44].'" name="'.$tempName[44].'" value="'.$tempValue[44].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[45].'" name="'.$tempName[45].'" value="'.$tempValue[45].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[46].'" name="'.$tempName[46].'" value="'.$tempValue[46].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[47].'" name="'.$tempName[47].'" value="'.$tempValue[47].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[48].'" name="'.$tempName[48].'" value="'.$tempValue[48].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[49].'" name="'.$tempName[49].'" value="'.$tempValue[49].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[50].'" name="'.$tempName[50].'" value="'.$tempValue[50].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[51].'" name="'.$tempName[51].'" value="'.$tempValue[51].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[52].'" name="'.$tempName[52].'" value="'.$tempValue[52].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[53].'" name="'.$tempName[53].'" value="'.$tempValue[53].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[54].'" name="'.$tempName[54].'" value="'.$tempValue[54].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[55].'" name="'.$tempName[55].'" value="'.$tempValue[55].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[56].'" name="'.$tempName[56].'" value="'.$tempValue[56].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[57].'" name="'.$tempName[57].'" value="'.$tempValue[57].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[58].'" name="'.$tempName[58].'" value="'.$tempValue[58].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[59].'" name="'.$tempName[59].'" value="'.$tempValue[59].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[60].'" name="'.$tempName[60].'" value="'.$tempValue[60].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[61].'" name="'.$tempName[61].'" value="'.$tempValue[61].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[62].'" name="'.$tempName[62].'" value="'.$tempValue[62].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[63].'" name="'.$tempName[63].'" value="'.$tempValue[63].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[64].'" name="'.$tempName[64].'" value="'.$tempValue[64].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[65].'" name="'.$tempName[65].'" value="'.$tempValue[65].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[66].'" name="'.$tempName[66].'" value="'.$tempValue[66].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[67].'" name="'.$tempName[67].'" value="'.$tempValue[67].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[68].'" name="'.$tempName[68].'" value="'.$tempValue[68].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[69].'" name="'.$tempName[69].'" value="'.$tempValue[69].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[70].'" name="'.$tempName[70].'" value="'.$tempValue[70].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[71].'" name="'.$tempName[71].'" value="'.$tempValue[71].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[72].'" name="'.$tempName[72].'" value="'.$tempValue[72].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[73].'" name="'.$tempName[73].'" value="'.$tempValue[73].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[74].'" name="'.$tempName[74].'" value="'.$tempValue[74].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[75].'" name="'.$tempName[75].'" value="'.$tempValue[75].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[76].'" name="'.$tempName[76].'" value="'.$tempValue[76].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[77].'" name="'.$tempName[77].'" value="'.$tempValue[77].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[78].'" name="'.$tempName[78].'" value="'.$tempValue[78].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[79].'" name="'.$tempName[79].'" value="'.$tempValue[79].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[80].'" name="'.$tempName[80].'" value="'.$tempValue[80].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[81].'" name="'.$tempName[81].'" value="'.$tempValue[81].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[82].'" name="'.$tempName[82].'" value="'.$tempValue[82].'">
                                </td>
                            </tr>
                            <tr class="bt-lang">
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[83].'" name="'.$tempName[83].'" value="'.$tempValue[83].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[84].'" name="'.$tempName[84].'" value="'.$tempValue[84].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[85].'" name="'.$tempName[85].'" value="'.$tempValue[85].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[86].'" name="'.$tempName[86].'" value="'.$tempValue[86].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[87].'" name="'.$tempName[87].'" value="'.$tempValue[87].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[88].'" name="'.$tempName[88].'" value="'.$tempValue[88].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[89].'" name="'.$tempName[89].'" value="'.$tempValue[89].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[90].'" name="'.$tempName[90].'" value="'.$tempValue[90].'">
                                </td>
                                <td>
                                    <input type="text" class="truncated-text" title="'.$tempValue[91].'" name="'.$tempName[91].'" value="'.$tempValue[91].'">
                                </td>
                            </tr> 
                            <tr>
                                <td colspan="9" rowspan="2">
                                    <label class="font-weight-bold">'.normal_case($tempName[92]).':</label>
                                    <textarea name="'.$tempName[92].'" placeholder="'.normal_case($tempName[92]).'" class="">'.$tempValue[92].'</textarea>                    
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

    public function addSift(Request $request)
    {
        $user = Auth::user();
        $lesson = new Lesson;
        $lesson->student_id = $request->id;
        $lesson->template_id = Lesson::SIFT;
        $lesson->lesson_date = Carbon::now()->format('Y-m-d');
        $lesson->lesson_json = $this->getLessonJson(Lesson::SIFT);
        $lesson->created_by = $user->id;
        $lesson->updated_by = $user->id;
        $r = $lesson->save();

        if($r)
        {            
            session(['lessonMsg' => 'Lesson added Successfully']);
            $result = ['status' => true, 'message' => '', 'data' => []];
            return response()->json($result);
        }
        else
        {
            $result = ['status' => false, 'message' => 'Error', 'data' => []];
            return response()->json($result);
        }
    }

    public function addBtLang(Request $request)
    {
        $user = Auth::user();
        $lesson = new Lesson;
        $lesson->student_id = $request->id;
        $lesson->template_id = Lesson::BTLANG;
        $lesson->lesson_date = Carbon::now()->format('Y-m-d');
        $lesson->lesson_json = $this->getLessonJson(Lesson::BTLANG);
        $lesson->created_by = $user->id;
        $lesson->updated_by = $user->id;
        $r = $lesson->save();

        if($r)
        {            
            session(['lessonMsg' => 'Lesson added Successfully']);
            $result = ['status' => true, 'message' => '', 'data' => []];
            return response()->json($result);
        }
        else
        {
            $result = ['status' => false, 'message' => 'Error', 'data' => []];
            return response()->json($result);
        }
    }

    public function addIm(Request $request)
    {
        $user = Auth::user();
        $lesson = new Lesson;
        $lesson->student_id = $request->id;
        $lesson->template_id = Lesson::IM;
        $lesson->lesson_date = Carbon::now()->format('Y-m-d');
        $lesson->lesson_json = $this->getLessonJson(Lesson::IM);
        $lesson->created_by = $user->id;
        $lesson->updated_by = $user->id;
        $r = $lesson->save();

        if($r)
        {            
            session(['lessonMsg' => 'Lesson added Successfully']);
            $result = ['status' => true, 'message' => '', 'data' => []];
            return response()->json($result);
        }
        else
        {
            $result = ['status' => false, 'message' => 'Error', 'data' => []];
            return response()->json($result);
        }
    }

    public function addSand(Request $request)
    {
        $user = Auth::user();
        $lesson = new Lesson;
        $lesson->student_id = $request->id;
        $lesson->template_id = Lesson::SAND;
        $lesson->lesson_date = Carbon::now()->format('Y-m-d');
        $lesson->lesson_json = $this->getLessonJson(Lesson::SAND);
        $lesson->created_by = $user->id;
        $lesson->updated_by = $user->id;
        $r = $lesson->save();

        if($r)
        {            
            session(['lessonMsg' => 'Lesson added Successfully']);
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
                "vestibular" => "",
                "proprioception" => "",
                "muscle_tone" => "",
                "reflex" => "",
                "kinestesia" => "",
                "massage" => "",
                "tactile" => "",
                "vp" => "",                
                "emotions" => "",
                "ep" => "",
                "others" => "",
                "ft" => "",                
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
        else if($id == 3){
            $template = [

                "date" => Carbon::now()->format('d-m-Y'),
                "setting_changes" => "",
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
                "activity5" => "",
                "duration5" => "",
                "taskavg5" => "",
                "varavg5" => "",
                "sro5" => "",
                "e5" => "",
                "l5" => "",
                "highestiar5" => "",
                "burst5" => "",
                "activity6" => "",
                "duration6" => "",
                "taskavg6" => "",
                "varavg6" => "",
                "sro6" => "",
                "e6" => "",
                "l6" => "",
                "highestiar6" => "",
                "burst6" => "",
                "activity7" => "",
                "duration7" => "",
                "taskavg7" => "",
                "varavg7" => "",
                "sro7" => "",
                "e7" => "",
                "l7" => "",
                "highestiar7" => "",
                "burst7" => "",
                "activity8" => "",
                "duration8" => "",
                "taskavg8" => "",
                "varavg8" => "",
                "sro8" => "",
                "e8" => "",
                "l8" => "",
                "highestiar8" => "",
                "burst8" => "",
                "activity9" => "",
                "duration9" => "",
                "taskavg9" => "",
                "varavg9" => "",
                "sro9" => "",
                "e9" => "",
                "l9" => "",
                "highestiar9" => "",
                "burst9" => "",
                "activity10" => "",
                "duration10" => "",
                "taskavg10" => "",
                "varavg10" => "",
                "sro10" => "",
                "e10" => "",
                "l10" => "",
                "highestiar10" => "",
                "burst10" => "",
                "comments" => ""
            ];

        } 
        else {
            $template = [

                "date" => Carbon::now()->format('d-m-Y'),
                "trainer" => Auth::user()->first_name,
                // "duration" => "",
                "description" => "",                
            ];
        }

        return json_encode($template);
    }

    public function update(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();

        $user = Auth::user();
            
        if(isset($request->student_id) && isset($request->update_id))
        {
            $lesson = Lesson::find($request->update_id);
            $changed = false;

            if($lesson)
            {
                // if($lesson->created_by != $user->id && $user->role_type != '1')
                // {
                //     return response()->json(['message' => 'Only person who post can edit'], 422);
                // }
            }
            else{
                return response()->json(['message' => 'Lesson not found'], 422);
            }

            // Convert date to db date formate
            $lesson_date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

            if($lesson->lesson_date != $lesson_date)
            {
                $changed = true;
            }

            $lessonLog = LessonLog::where('student_id',$request->student_id)->where('lesson_date',$lesson_date)->where('program', 'SI/FT')->where('deleted_at',null)->first();


            $lessonLogNew = LessonLog::where('lesson_id', $request->update_id)->first();

            if(!$request->duplicate)
            {                    
                if($lessonLog && $changed)
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
            if(!$lessonLogNew)
            {
                $lessonLogNew = new LessonLog;
            }
            $lessonLogNew->student_id = $request->student_id;
            $lessonLogNew->hours = $request->duration;
            $lessonLogNew->lesson_date = Carbon::parse($dt)->format('Y-m-d');
            $lessonLogNew->program = 'SI/FT';
            $lessonLogNew->created_by = $lessonLogNew->created_by ?? Auth::user()->id;
            $lessonLogNew->lesson_id = $lesson->id;
            $lessonLogNew->save();
            $rl = $lessonLogNew->save();

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
            $student->remaining_hours = $hoursRemaining;
            $student->is_past = ($hoursRemaining) ? 0 : 1;
            $student->save();

            // lesson log entry : Ends
            
            if($rl)
            {            
                $result = ['status' => true, 'message' => 'form saved Successfully', 'data' => []];
                return response()->json($result);
            }
            else
            {
                $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
                return response()->json($result);
                
            }

        }
    }

    public function btUpdate(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();
        $user = Auth::user();
            
        if(isset($request->student_id) && isset($request->update_id))
        {
            $lesson = Lesson::find($request->update_id);
            $changed = false;

            if($lesson)
            {
                // if($lesson->created_by != $user->id && $user->role_type != '1')
                // {
                //     return response()->json(['message' => 'Only person who post can edit'], 422);
                // }
            }
            else{
                return response()->json(['message' => 'Lesson not found'], 422);
            }

            // Convert date to db date formate
            $lesson_date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');            

            if($lesson->lesson_date != $lesson_date)
            {
                $changed = true;
            }

            $lessonLog = LessonLog::where('student_id',$request->student_id)->where('lesson_date',$lesson_date)->where('program', 'BT/Lang')->where('deleted_at',null)->first();

            $lessonLogNew = LessonLog::where('lesson_id', $request->update_id)->first();

            if(!$request->duplicate && $changed)
            {                    
                if($lessonLog)
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
            if(!$lessonLogNew)
            {
                $lessonLogNew = new LessonLog;
            }
            $lessonLogNew->student_id = $request->student_id;
            $lessonLogNew->hours = $request->duration;
            $lessonLogNew->lesson_date = Carbon::parse($dt)->format('Y-m-d');
            $lessonLogNew->program = 'BT/Lang';
            $lessonLogNew->created_by = $lessonLogNew->created_by ?? Auth::user()->id;
            $lessonLogNew->lesson_id = $lesson->id;
            $lessonLogNew->save();
            $rl = $lessonLogNew->save();

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
            $student->remaining_hours = $hoursRemaining;
            $student->is_past = ($hoursRemaining) ? 0 : 1;
            $student->save();

            // lesson log entry : Ends
            
            if($rl)
            {            
                $result = ['status' => true, 'message' => 'form saved Successfully', 'data' => []];
                return response()->json($result);
            }
            else
            {
                $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
                return response()->json($result);
            }

        }
    }

    public function sandUpdate(Request $request)
    {
        // $this->validator($request->all())->validate();

        $data = $request->all();
        $user = Auth::user();
            
        if(isset($request->student_id) && isset($request->update_id))
        {
            $lesson = Lesson::find($request->update_id);

            if($lesson)
            {
                // if($lesson->created_by != $user->id && $user->role_type != '1')
                // {
                //     return response()->json(['message' => 'Only person who post can edit'], 422);
                // }
            }
            else{
                return response()->json(['message' => 'Lesson not found'], 422);
            }

            // Convert date to db date formate
            $lesson_date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

            // $lessonLog = LessonLog::where('student_id',$request->student_id)->where('lesson_date',$lesson_date)->where('deleted_at',null)->first();

            // if(!$request->duplicate)
            // {                    
            //     if($lessonLog)
            //     {
            //         $result = ['status' => true, 'match' => 1, 'data' => []];
            //         return response()->json($result);
            //     }
            // }
            
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
                                    $notification = Notification::where('student_id',$request->student_id)->where('user_id',$uId)->where('case_id',$request->update_id)->where('case_type',7)->where('updated_by',Auth::user()->id)->where('deleted_at',null)->first();

                                    if($notification)
                                        continue;                        

                                    $notification = new Notification;
                                    $notification->student_id = $request->student_id;
                                    $notification->user_id = $uId;
                                    $notification->case_id = $request->update_id;
                                    $notification->case_type = 7; // 1 : Case Management Meeting, 2: Parent Review Session, 3: comments, 4: SI/FT, 5: BT/Lang, 6: IM, 7: Sand
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
            // if(!$lessonLog)
            // {
            //     $lessonLog = new LessonLog;
            // }
            // $lessonLog->student_id = $request->student_id;
            // $lessonLog->hours = $request->duration;
            // $lessonLog->lesson_date = Carbon::parse($dt)->format('Y-m-d');
            // $lessonLog->program = 'Sand';
            // $lessonLog->created_by = $lessonLog->created_by ?? Auth::user()->id;
            // $lessonLog->lesson_id = $lesson->id;
            // $lessonLog->save();
            // $rl = $lessonLog->save();

            // $totalHours = DB::table('add_hour_logs')
            //        ->where('add_hour_logs.deleted_at',null)
            //        ->where('add_hour_logs.student_id',$request->student_id)
            //        ->sum('hours');

            // $finishedHours = DB::table('lesson_hour_logs')
            //                ->where('lesson_hour_logs.deleted_at',null)
            //                ->where('lesson_hour_logs.student_id',$request->student_id)
            //                ->sum('hours');

            // $hoursRemaining = $totalHours - $finishedHours;

            // if($hoursRemaining < 0)
            //     $hoursRemaining = 0;

            // $student = Student::find($request->student_id);
            // $student->remaining_hours = $hoursRemaining;
            // $student->is_past = ($hoursRemaining) ? 0 : 1;
            // $student->save();

            // lesson log entry : Ends
            
            if($r)
            {            
                $result = ['status' => true, 'message' => 'form saved Successfully', 'data' => []];
                return response()->json($result);
            }
            else
            {
                $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
                return response()->json($result);
            }

        }
    }

    public function imUpdate(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
            
        if(isset($request->student_id) && isset($request->update_id))
        {
            $lesson = Lesson::find($request->update_id);

            if($lesson)
            {
                // if($lesson->created_by != $user->id && $user->role_type != '1')
                // {
                //     // return response()->json(['message' => 'Only person who post can edit'], 422);
                //     return redirect()->back()->with(['flash_message_error' => 'Only person who post can edit']);
                // }
            }
            else{
                return response()->json(['message' => 'Lesson not found'], 422);
            }
            
            $temps = json_decode($lesson->lesson_json,true);
            
            foreach($temps as $k => $v)
            {                
                foreach ($data as $key => $value) {
                    if($k == $key)
                    {
                        $temps[$k] = $value;
                        if($key == 'comments' && $value)
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
                                    $notification = Notification::where('student_id',$lesson->student_id)->where('user_id',$uId)->where('case_id',$request->update_id)->where('case_type',6)->where('updated_by',Auth::user()->id)->where('deleted_at',null)->first();

                                    if($notification)
                                        continue;                        

                                    $notification = new Notification;
                                    $notification->student_id = $lesson->student_id;
                                    $notification->user_id = $uId;
                                    $notification->case_id = $request->update_id;
                                    $notification->case_type = 6; // 1 : Case Management Meeting, 2: Parent Review Session, 3: comments, 4: SI/FT, 5: BT/Lang, 6: IM
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
        $user = Auth::user();
        $model = Lesson::find($request->id);   
        if($model)
        {
            if($model->created_by != $user->id && $user->role_type != '1')
            {
                return response()->json(['message' => 'Only person who post can delete'], 422);
            }
        }             
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
