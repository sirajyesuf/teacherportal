<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Notification;
use App\Announcement;
use App\AnnouncementRecipient;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $users = Student::current()->orderBy('name','ASC')->search($request->q)->paginate(20000);

        $notifications = Notification::query()
                    ->leftjoin('users','notifications.updated_by','users.id')
                    ->leftjoin('students','notifications.student_id','students.id')
                    ->where('notifications.user_id',$user->id)
                    ->where('notifications.deleted_at',null)
                    ->select('users.first_name','students.name','notifications.student_id','notifications.is_read','notifications.case_id','notifications.case_type','notifications.created_at','notifications.id')
                    ->orderBy('notifications.created_at','desc')
                    ->limit(10)
                    ->get();

        $unReadNotificationCount = Notification::query()
                    ->leftjoin('users','notifications.updated_by','users.id')
                    ->where('notifications.user_id',$user->id)
                    ->where('notifications.deleted_at',null)
                    ->where('notifications.is_read',0)
                    ->select('users.first_name','notifications.student_id','notifications.created_at','notifications.id')
                    ->orderBy('notifications.created_at','desc')
                    ->count();

        $announcementsNots = Announcement::join('announcement_recipients','announcements.id','announcement_recipients.announcement_id')
                    ->join('users','announcement_recipients.user_id','users.id')
                    ->where('announcement_recipients.user_id',$user->id)
                    ->select('announcement_recipients.id as anrId','users.*','announcements.*','announcement_recipients.*','announcements.id as id')
                    ->orderBy('announcements.created_at','desc')
                    ->limit(10)
                    ->get();

        // Unread Annoucement Count
        $unreadCount = AnnouncementRecipient::where('user_id', $user->id)
                    ->where('read', false)
                    ->count();

        $users->appends (array ('q' => $request->q));

        $q = '';

        if(isset($request->q))
            $q = $request->q;

        return view('students.index', compact('users','notifications','unReadNotificationCount','announcementsNots','unreadCount','q'));
    }
}
