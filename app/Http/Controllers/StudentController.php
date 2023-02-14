<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Student;
use App\Tls;
use App\Notification;
use Carbon\Carbon;
use Auth;
use DB;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('lesson.template')->only('lesson');
    }

    public function pastStudent(Request $request)
    {
        $notifications = Notification::query()
                    ->leftjoin('users','notifications.updated_by','users.id')
                    ->leftjoin('students','notifications.student_id','students.id')
                    ->where('notifications.user_id',Auth::user()->id)
                    ->where('notifications.deleted_at',null)
                    ->select('users.first_name','students.name','notifications.student_id','notifications.is_read','notifications.case_id','notifications.case_type','notifications.created_at')
                    ->orderBy('notifications.created_at','desc')
                    ->limit(10)
                    ->get();

        $unReadNotificationCount = Notification::query()
                    ->leftjoin('users','notifications.updated_by','users.id')
                    ->where('notifications.user_id',Auth::user()->id)
                    ->where('notifications.deleted_at',null)
                    ->where('notifications.is_read',0)
                    ->select('users.first_name','notifications.student_id','notifications.created_at')
                    ->orderBy('notifications.created_at','desc')                    
                    ->count();

        $users = Student::past()->orderBy('name','ASC')->search($request->q)->paginate(20000);

        $users->appends (array ('q' => $request->q));

        $q = '';

        if(isset($request->q))
            $q = $request->q;           

        return view('students.past-students', compact('users','q','notifications','unReadNotificationCount'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function add(Request $request)
    {
        $this->validator($request->all())->validate();

        // $request->request->add(['role' => 'student','role_type' => 3]);

        $user = Student::create($request->all());
        
        if($user)
        {
            // toastr()->success('Student created Successfully');
            session(['successMsg' => 'Student created Successfully']);
            return redirect()->route('home');
        }
        else
        {
            toastr()->error('An error has occurred please try again later.');
            return back();
        }
    }

    public function delete(Request $request)
    {
        $model = Student::find($request->id);                
        $model->updated_by = Auth::user()->id;
        $model->deleted_at = Carbon::now();
        if($model->save()){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],            
        ]);
    }

    public function dateUpdate(Request $request)
    {
        if($request->id)
        {
            $user = Student::find($request->id);
            $user->appointment_date = ($request->date)?$request->date:now();
            $user->is_appointment_done = 0;
            $user->updated_by = Auth::user()->id;
            $r = $user->save();

            if($r)
            {
                // toastr()->success('Date changes Successfully');                
                $result = ['status' => true, 'message' => 'Date changes success.', 'data' => []];
                return response()->json($result);
            }
            else
            {
                // toastr()->error('An error has occurred please try again later.');
                $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
                return response()->json($result);
            }
        }
    }

    public function lesson(Request $request)
    {
        return view('lessons.index');
    }

    public function selectTempate(Request $request)
    {
        return view('lessons.template');
    }

    public function profile(Student $student)
    {
        $completeHours = DB::table('lesson_hour_logs')
                       ->join('students','lesson_hour_logs.student_id','students.id')
                       ->leftjoin('users','lesson_hour_logs.created_by','users.id')
                       ->where('lesson_hour_logs.deleted_at',null)
                       ->where('lesson_hour_logs.student_id',$student->id)
                       ->select('lesson_hour_logs.hours','lesson_hour_logs.created_at','lesson_hour_logs.lesson_date','users.first_name')
                       ->orderBy('lesson_hour_logs.lesson_date','desc')
                       ->paginate(5,['*'], 'complete');

        $addedHours = DB::table('add_hour_logs')
                       ->join('students','add_hour_logs.student_id','students.id')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$student->id)
                       ->select('add_hour_logs.id as aId','add_hour_logs.hours','add_hour_logs.created_at','add_hour_logs.notes','students.name')
                       ->orderBy('add_hour_logs.created_at','desc')
                       ->paginate(5,['*'], 'added');        

        $totalHours = DB::table('add_hour_logs')
                       ->where('add_hour_logs.deleted_at',null)
                       ->where('add_hour_logs.student_id',$student->id)
                       ->sum('hours');

        $finishedHours = DB::table('lesson_hour_logs')
                       ->where('lesson_hour_logs.deleted_at',null)
                       ->where('lesson_hour_logs.student_id',$student->id)
                       ->sum('hours');

        // for blue background of tls
        $lesson_date_array = DB::table('lesson_hour_logs')
                        ->where('student_id',$student->id)
                        ->where('deleted_at',null)
                        ->pluck('lesson_date')
                        ->toArray();

        $hoursRemaining = $totalHours - $finishedHours;

        $tlss = DB::table('tls')
               ->join('students','tls.student_id','students.id')
               ->where('tls.deleted_at',null)
               ->where('tls.student_id',$student->id)
               ->select('tls.*')
               ->get();

        if($hoursRemaining < 0)
            $hoursRemaining = 0;                       

        return view('students.profile',compact('student','completeHours','addedHours','hoursRemaining','finishedHours','tlss','lesson_date_array'));
    }

    public function descriptionUpdate(Request $request)
    {
        if($request->student_id)
        {
            $student = Student::find($request->student_id);
            $student->description = $request->description;
            $student->updated_by = Auth::user()->id;
            $r = $student->save();

            if($r)
            {            
                // toastr()->success('Profile updated Successfully');
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'Profile description updated Successfully!');
                return redirect()->back();
            }           
        }
        toastr()->error('An error has occurred please try again later.');
        return back();
    }

    public function dateCheck(Request $request)
    {
        if($request->id)
        {
            $user = Student::find($request->id);
            $user->is_appointment_done = 1; 
            $user->updated_by = Auth::user()->id;
            $r = $user->save();

            if($r)
            {                
                $result = ['status' => true, 'message' => 'Appointment marked completed', 'data' => []];
                return response()->json($result);
            }
            else
            {                
                $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
                return response()->json($result);
            }
        }
        $result = ['status' => false, 'message' => 'An error has occurred please try again later.', 'data' => []];
        return response()->json($result);
    }
}
