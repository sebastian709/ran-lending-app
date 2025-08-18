<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
        $this->middleware('auth');
    }

    public function testNotif()
    {
        return view('admin.testing-only.test2notif');
    }

    public function AdminViewPage()
    {
        return view('admin.pages.notification.index');
    }

    public function BorrowerViewPage()
    {
        return view('borrower.pages.notification-page');
    }

    public function send(Request $request)
    {
        $userIds = [];
        switch ($request->target_type) {
            case 1:

                if ($request->level_id == 1) {

                    $userIds = DB::table('users')
                        ->where('is_admin', 1)
                        ->pluck('id')
                        ->toArray();
                } else if ($request->level_id == 2) {

                    $userIds = DB::table('users')
                        ->where('is_admin', 0)
                        ->pluck('id')
                        ->toArray();
                }

                break;
            case 2:
                $userIds = [(int) $request->user_id];
                break;
            case 3:
                $userIds = $request->group_user_id;
                break;
            default:
                break;
        }
        $this->firebase->sendNotification($request->table_id, $userIds);

        foreach ($userIds as $userId) {
            DB::table('notification_data')->insert([
                'user_id' => $userId,
                'is_read' => 0,
                'icon' => $request->icon,
                'message' => $request->message,
                'data_url' => $request->data_url,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return response()->json(['status' => 'Notification sent!']);
    }
}
