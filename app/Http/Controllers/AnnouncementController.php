<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\Announcement;
use App\AnnouncementRecipient;
use App\User;
use Validator;
use Auth;
use DB;

class AnnouncementController extends Controller
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
        
        $announcements = $user->receivedAnnouncements()
            ->whereHas('announcement', function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('content', 'like', '%' . $request->q . '%');
            })
            ->latest()
            ->get();

        $announcementsbyUser = $user->createdAnnouncements()->latest()->get();

        $q = '';

        if(isset($request->q))
            $q = $request->q; 

        return view('announcements.index',compact('notifications','unReadNotificationCount','announcementsNots','announcements','unreadCount','q'));
    }    

    public function getRecipientName(Request $request)
    {
        $search = $request->search;

        $items = DB::table('users')
            ->where('deleted_at', null)            
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'LIKE', '%'.$search.'%')                      
                      ->orWhere('users.last_name', 'LIKE', '%'.$search.'%');
            })
            ->select('id','first_name')
            ->get();        

        if($items)
        {
            $response = array();
                foreach($items as $key => $item){
                    
                    $response[$key] = array(
                        "id"=>$item->id,
                        "text"=>$item->first_name
                    );
            }
            return response()->json($response); 
        }
    }

    public function addall(Request $request)
    {
        if($request->ajax()) {
            $rules = array(
                'date' => 'required|date',    
                'trainer' => 'required',
                'title' => 'required|string|max:150',
                'content' => 'required|max:5000'
            );
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
                return response()->json($result);
            }else{                
                
                $announcement = new Announcement([
                    'trainer_id' => auth()->user()->id,
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                    'is_all' => true
                ]);

                $r = $announcement->save();

                $allUsers = User::all(); // Fetch all users

                foreach ($allUsers as $user) {
                    $recipient = new AnnouncementRecipient([
                        'user_id' => $user->id,
                        'announcement_id' => $announcement->id,                        
                    ]);

                    $recipient->save();
                }

                if($r)
                {
                    $result = ['status' => true,  'name' => $request->name, 'message' => 'Announcement created successfully.', 'data' => []];
                }else{
                    $result = ['status' => false, 'message' => 'Announcement create failed.', 'data' => []];
                }
            }
            return response()->json($result);
        }
    }

    public function addIndividual(Request $request)
    {
        if($request->ajax()) {
            $rules = array(
                'date' => 'required|date',    
                'trainer' => 'required',
                'title' => 'required|string|max:150',
                'content' => 'required|max:5000',
                'recipients' => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                $result = ['status' => false, 'message' => $validator->errors(), 'data' => []];
                return response()->json($result);
            }else{                
                
                $announcement = new Announcement([
                    'trainer_id' => auth()->user()->id,
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                    'is_all' => false
                ]);

                $r = $announcement->save();

                $recipients = $request->recipients;
                
                if(count($recipients))
                {                    
                    foreach ($recipients as $rec) {
                        $recipient = new AnnouncementRecipient([
                            'user_id' => $rec,
                            'announcement_id' => $announcement->id,                        
                        ]);

                        $recipient->save();
                    }
                }

                if($r)
                {
                    $result = ['status' => true,  'name' => $request->name, 'message' => 'Announcement created successfully.', 'data' => []];
                }else{
                    $result = ['status' => false, 'message' => 'Announcement create failed.', 'data' => []];
                }
            }
            return response()->json($result);
        }
    }
}
