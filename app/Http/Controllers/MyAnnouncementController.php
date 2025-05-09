<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Notification;
use App\Announcement;
use App\AnnouncementRecipient;
use Auth;

class MyAnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function index(Request $request)
    {
        $user = auth()->user();

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


        $announcementsbyUser = $user->createdAnnouncements()
            ->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('content', 'like', '%' . $request->q . '%');
            })
            ->latest()
            ->get();        

        $q = '';

        if(isset($request->q))
            $q = $request->q; 

        return view('announcements.my-announcement',compact('notifications','unReadNotificationCount','announcementsNots','unreadCount','announcementsbyUser','q'));
    }
}
