<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function getNotificationData(Request $request)
    {
        $userId = auth()->id();
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        // Total count ng lahat ng notifications (na may status = 1)
        $totalCount = DB::table('notification_data')
            ->where('notification_data.user_id', $userId)
            ->where('notification_data.status', 1)
            ->where('notification_data.is_read', 0)
            ->count();

        // Paginated result
        $result = DB::table('notification_data')
            ->select(
                'notification_data.id',
                'notification_data.user_id',
                'notification_data.is_read',
                'notification_data.icon',
                'notification_data.message',
                'notification_data.data_url',
                'notification_data.created_at'
            )
            ->where('notification_data.user_id', $userId)
            ->where('notification_data.status', 1)
            ->orderBy('notification_data.created_at', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                $created = Carbon::parse($item->created_at);
                $diffInSeconds = $created->diffInSeconds(Carbon::now());

                if ($diffInSeconds < 3600) {
                    $item->time_ago = floor($diffInSeconds / 60) . 'm';
                } elseif ($diffInSeconds < 86400) {
                    $item->time_ago = floor($diffInSeconds / 3600) . 'h';
                } elseif ($diffInSeconds < 604800) {
                    $item->time_ago = floor($diffInSeconds / 86400) . 'd';
                } elseif ($diffInSeconds < 31536000) {
                    $item->time_ago = floor($diffInSeconds / 604800) . 'w';
                } else {
                    $item->time_ago = floor($diffInSeconds / 31536000) . 'y';
                }

                return $item;
            });

        return response()->json([
            'total' => $totalCount,
            'data' => $result
        ]);
    }

    public function markAllRead()
    {
        try {
            $userId = auth()->id();

            // I-update lahat ng notification ng user na hindi pa read
            DB::table('notification_data')
                ->where('user_id', $userId)
                ->where('status', 1) // active notifications
                ->where('is_read', 0) // hindi pa nababasa
                ->update([
                    'is_read' => 1,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearAllNotifications()
    {
        try {
            $userId = auth()->id();
            DB::table('notification_data')
                ->where('user_id', $userId)
                ->where('status', 1)
                ->update([
                    'status' => 0,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

}
