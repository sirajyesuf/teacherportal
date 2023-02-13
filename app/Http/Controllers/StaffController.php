<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Notification;
use Auth;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin')->only('index');
    }
    
    public function index(Request $request)
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

        $users = User::staff()->orderBy('first_name','ASC')->search($request->q)->paginate(20000);

        $users->appends (array ('q' => $request->q));

        $q = '';

        if(isset($request->q))
            $q = $request->q;

        return view('staff.index',compact('users','q','notifications','unReadNotificationCount'));
    }
}
