<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\CaseNote;
use App\CaseManagement;
use App\ParentReview;
use App\Comment;
use App\Notification;
use Carbon\Carbon;
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


            $casemgmts = CaseManagement::where('deleted_at',null)->where('student_id',$request->id)->orderBy('date','desc')->orderBy('created_at','desc')->get();
            $parentreviews = ParentReview::where('deleted_at',null)->where('student_id',$request->id)->orderBy('date','desc')->orderBy('created_at','desc')->get();
            $comments = Comment::where('deleted_at',null)->where('student_id',$request->id)->orderBy('date','desc')->orderBy('created_at','desc')->get();

            $data = $casemgmts->concat($parentreviews);
            $data = $data->concat($comments);
            $data = $data->sortByDesc('created_at')->sortByDesc('date');

        }

        return view('casenotes.index',compact('data','user','caseNote','finishedHours','hoursRemaining','casemgmts','parentreviews','comments'));
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

    public function addCmm(Request $request)
    {
        $user = auth()->user();
        $caseMgmt = new CaseManagement;
        $caseMgmt->student_id = $request->id;
        $caseMgmt->date = Carbon::now()->format('Y-m-d');
        $caseMgmt->trainer = $user->first_name;
        $caseMgmt->trainer_id = $user->id;
        $caseMgmt->updated_by = $user->id;
        $r = $caseMgmt->save();

        if($r)
        {
            $result = ['status' => true, 'message' => 'Created successfully'];
        }
        else
        {
            $result = ['status' => false, 'message' => 'Create fail'];
        }

        return response()->json($result);
    }

    public function addPrs(Request $request)
    {   
        $user = auth()->user();

        $parentReview = new ParentReview;
        $parentReview->student_id = $request->id;
        $parentReview->date = Carbon::now()->format('Y-m-d');
        $parentReview->trainer = $user->first_name;
        $parentReview->trainer_id = $user->id;
        $parentReview->updated_by = $user->id;
        $r = $parentReview->save();

        if($r)
        {
            $result = ['status' => true, 'message' => 'Created successfully'];
        }
        else
        {
            $result = ['status' => false, 'message' => 'Create fail'];
        }

        return response()->json($result);
    }

    public function addCom(Request $request)
    {
        $user = auth()->user();

        $comment = new Comment;
        $comment->student_id = $request->id;
        $comment->date = Carbon::now()->format('Y-m-d');
        $comment->trainer = $user->first_name;
        $comment->trainer_id = $user->id;
        $comment->updated_by = $user->id;
        $r = $comment->save();

        if($r)
        {
            $result = ['status' => true, 'message' => 'Created successfully'];
        }
        else
        {
            $result = ['status' => false, 'message' => 'Create fail'];
        }

        return response()->json($result);
    }

    public function updateCmm(Request $request)
    {
        $user = auth()->user();
        $r = '';

        if($request->update_id)
        {
            $caseMgmt = CaseManagement::find($request->update_id);
            $caseMgmt->date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            $caseMgmt->package = $request->package;
            $caseMgmt->num = $request->num;
            $caseMgmt->description = $request->description;
            $caseMgmt->updated_by = $user->id;
            $r = $caseMgmt->save();

            if($request->description)
            {
                $notIds = [];                
                $str = $request->description;
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
                        $notification = Notification::where('student_id',$request->student_id)->where('user_id',$uId)->where('case_id',$request->update_id)->where('case_type',1)->where('updated_by',$user->id)->where('deleted_at',null)->first();

                        if($notification)
                            continue;                        

                        if($uId)
                        {                            
                            $notification = new Notification;
                            $notification->student_id = $request->student_id;
                            $notification->user_id = $uId;
                            $notification->case_id = $request->update_id;
                            $notification->case_type = 1; // 1 : Case Management Meeting, 2: Parent Review Session                        
                            $notification->updated_by = $user->id;
                            $notification->save();
                        }
                    }
                }
            }

        }

        if($r)
        {
            session(['dataUpdated' => 'Case Management Meeting saved']);
            return back();            
        }
        else
        {            
            session(['updateFail' => 'An error has occurred please try again later.']);
            return back();
        }
    }

    public function updatePrs(Request $request)
    {
        $user = auth()->user();
        $r = '';

        if($request->update_id)
        {
            $parentRw = ParentReview::find($request->update_id);
            $parentRw->date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');            
            $parentRw->description = $request->description;
            $parentRw->updated_by = $user->id;
            $r = $parentRw->save();

            if($request->description)
            {
                $notIds = [];                
                $str = $request->description;
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
                        $notification = Notification::where('student_id',$request->student_id)->where('user_id',$uId)->where('case_id',$request->update_id)->where('case_type',1)->where('updated_by',$user->id)->where('deleted_at',null)->first();

                        if($notification)
                            continue;                        

                        if($uId)
                        {                            
                            $notification = new Notification;
                            $notification->student_id = $request->student_id;
                            $notification->user_id = $uId;
                            $notification->case_id = $request->update_id;
                            $notification->case_type = 2; // 1 : Case Management Meeting, 2: Parent Review Session                        
                            $notification->updated_by = $user->id;
                            $notification->save();
                        }
                    }
                }
            }
        }

        if($r)
        {
            session(['dataUpdated' => 'Parent Review Session saved']);
            return back();
        }
        else
        {
            session(['updateFail' => 'An error has occurred please try again later.']);
            return back();            
        }
    }

    public function updateCom(Request $request)
    {
        $user = auth()->user();
        $r = '';

        if($request->update_id)
        {
            $comment = Comment::find($request->update_id);
            $comment->date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            $comment->comments = $request->comments;
            $comment->updated_by = $user->id;
            $r = $comment->save();

            if($request->comments)
            {
                $notIds = [];                
                $str = $request->comments;
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
                    \Log::info($notIds);
                    foreach($notIds as $uId)
                    {                        
                        $notification = Notification::where('student_id',$request->student_id)->where('user_id',$uId)->where('case_id',$request->update_id)->where('case_type',1)->where('updated_by',$user->id)->where('deleted_at',null)->first();

                        if($notification)
                            continue;               

                        if($uId)
                        {                            
                            $notification = new Notification;
                            $notification->student_id = $request->student_id;
                            $notification->user_id = $uId;
                            $notification->case_id = $request->update_id;
                            $notification->case_type = 3; // 1 : Case Management Meeting, 2: Parent Review Session, 3: Comments                        
                            $notification->updated_by = $user->id;
                            $notification->save();
                        }            

                    }
                }
            }
        }

        if($r)
        {       
            session(['dataUpdated' => 'Comments saved']);
            return back();
        }
        else
        {
            session(['updateFail' => 'An error has occurred please try again later.']);
            return back();
        }
    }

    public function deleteCmm(Request $request)
    {       

        $model = CaseManagement::find($request->id);                
        $model->updated_by = Auth::user()->id;
        $model->deleted_at = Carbon::now();        

        if($model->save()){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);

    }

    public function deletePrs(Request $request)
    {          

        $model = ParentReview::find($request->id);                
        $model->updated_by = Auth::user()->id;
        $model->deleted_at = Carbon::now();        

        if($model->save()){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
    }

    public function deleteCom(Request $request)
    {       

        $model = Comment::find($request->id);                
        $model->updated_by = Auth::user()->id;
        $model->deleted_at = Carbon::now();        

        if($model->save()){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
    }

    public function test()
    {
        $casenote = CaseManagement::all();
        $parentReview = ParentReview::all();
        $comment = Comment::all();

        $data = $casenote->concat($parentReview);
        $data = $data->concat($comment);        
        $data = $data->sortByDesc('date');

        return view('data',compact('data'));

        echo "<pre>";
        print_r($data);
        die;
    }
}
