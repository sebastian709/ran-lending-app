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
        $request->validate([
            'table_id' => 'required|string|max:100',
            'target_type' => 'required|integer|in:1,2,3',
            'level_id' => 'nullable|integer|in:1,2',
            'user_id' => 'nullable|integer|exists:users,id',
            'group_user_id' => 'nullable|array|max:200',
            'group_user_id.*' => 'integer|exists:users,id',
            'icon' => 'nullable|string|max:255',
            'message' => 'required|string|max:500',
            'data_url' => ['nullable', 'string', 'max:255', 'regex:/^(\/[A-Za-z0-9_\-\/\?\=\&\.\#]*)?$/'],
        ]);

        $isAdmin = (int) auth()->user()->is_admin === 1;
        $targetType = (int) $request->target_type;

        // Non-admin users cannot broadcast to whole role groups.
        if (!$isAdmin && $targetType === 1) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $userIds = [];
        switch ($targetType) {
            case 1:
                if ((int) $request->level_id === 1) {
                    $userIds = DB::table('users')
                        ->where('is_admin', 1)
                        ->where('status', 1)
                        ->pluck('id')
                        ->toArray();
                } elseif ((int) $request->level_id === 2) {
                    $userIds = DB::table('users')
                        ->where('is_admin', 0)
                        ->where('status', 1)
                        ->pluck('id')
                        ->toArray();
                } else {
                    return response()->json(['message' => 'Invalid level_id for target_type 1'], 422);
                }
                break;
            case 2:
                if (empty($request->user_id)) {
                    return response()->json(['message' => 'user_id is required for target_type 2'], 422);
                }

                $exists = DB::table('users')
                    ->where('id', (int) $request->user_id)
                    ->where('status', 1)
                    ->exists();

                if (!$exists) {
                    return response()->json(['message' => 'Target user does not exist'], 422);
                }

                $userIds = [(int) $request->user_id];
                break;
            case 3:
                if (empty($request->group_user_id) || !is_array($request->group_user_id)) {
                    return response()->json(['message' => 'group_user_id is required for target_type 3'], 422);
                }

                $candidateIds = array_values(array_unique(array_map('intval', $request->group_user_id)));
                if (!$isAdmin && count($candidateIds) > 20) {
                    return response()->json(['message' => 'Forbidden'], 403);
                }
                $userIds = DB::table('users')
                    ->whereIn('id', $candidateIds)
                    ->where('status', 1)
                    ->pluck('id')
                    ->toArray();
                break;
            default:
                break;
        }

        if (empty($userIds)) {
            return response()->json(['message' => 'No recipients found for notification'], 422);
        }

        $message = $this->sanitizeMessage((string) $request->message);
        $icon = $this->sanitizeIcon((string) $request->icon);
        $dataUrl = trim((string) $request->data_url);

        $this->firebase->sendNotification($request->table_id, $userIds);

        foreach ($userIds as $userId) {
            DB::table('notification_data')->insert([
                'user_id' => $userId,
                'is_read' => 0,
                'icon' => $icon,
                'message' => $message,
                'data_url' => $dataUrl,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        return response()->json(['status' => 'Notification sent!']);
    }

    private function sanitizeIcon(string $icon): string
    {
        $icon = trim($icon);
        if ($icon === '') {
            return '';
        }

        // Only allow a single <i class="..."></i> icon snippet.
        if (preg_match('/^<i\\s+class="[\w\s\-\_]+"><\\/i>$/i', $icon) === 1) {
            return $icon;
        }

        return '';
    }

    private function sanitizeMessage(string $message): string
    {
        $message = trim($message);
        $message = strip_tags($message, '<p><b><strong><i><em><span><br>');

        // Remove inline event handlers (onclick, onerror, etc.)
        $message = preg_replace('/\son\w+\s*=\s*("|\').*?\\1/i', '', $message);
        $message = preg_replace('/\son\w+\s*=\s*[^\s>]+/i', '', $message);

        // Remove javascript: URIs if ever present in any attribute.
        $message = preg_replace('/javascript\s*:/i', '', $message);

        return $message;
    }

    public function getNotificationData(Request $request)
    {
        $userId = auth()->id();
        $limit = (int) $request->get('limit', 10);
        $offset = (int) $request->get('offset', 0);

        // Defensive bounds to prevent abusive large pagination windows.
        $limit = max(1, min($limit, 50));
        $offset = max(0, min($offset, 10000));

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
                $item->icon = $this->sanitizeIcon((string) $item->icon);
                $item->message = trim(strip_tags((string) $item->message));
                $item->data_url = $this->sanitizeDataUrl((string) $item->data_url);

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
            'data' => $result,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    private function sanitizeDataUrl(string $dataUrl): string
    {
        $dataUrl = trim($dataUrl);
        if ($dataUrl === '') {
            return '';
        }

        return preg_match('/^(\/[A-Za-z0-9_\-\/\?\=\&\.\#]*)$/', $dataUrl) === 1
            ? $dataUrl
            : '';
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
