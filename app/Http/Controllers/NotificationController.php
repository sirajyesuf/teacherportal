<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\AnnouncementRecipient;
use Auth;
use App\User;
use App\Student;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class NotificationController extends Controller
{
    public function readNotification(Request $request)
    {
        $uId = auth()->user()->id;
        $notifications = Notification::query()
                    ->where('notifications.user_id', $uId)
                    ->where('notifications.deleted_at',null)
                    ->where('notifications.is_read',0)
                    ->update(['notifications.is_read' => 1]);

        if($notifications)
        {
            $result = ['status' => true, 'message' => 'notification read.', 'data' => []];
        }
        else
        {
            $result = ['status' => false, 'message' => '', 'data' => []];
        }

        return response()->json($result);
    }

    public function readAnnNotification(Request $request)
    {
        $uId = auth()->user()->id;

        $result = AnnouncementRecipient::where('user_id',$uId)
                        ->where('read', 0)
                        ->update(['read' => 1]);

        if($result)
        {
            $result = ['status' => true, 'message' => 'notification read.', 'data' => []];
        }
        else
        {
            $result = ['status' => false, 'message' => '', 'data' => []];
        }

        return response()->json($result);
    }

    public function readSingleNotification(Request $request)
    {
        if ($request->ajax()) {
            if($request->id){
                Notification::where('id', $request->id)->update(['is_read' => true]);
                return response()->json(['status' => true]);
            }else{
                return response()->json(['status' => false]);
            }
        }
    }
}
