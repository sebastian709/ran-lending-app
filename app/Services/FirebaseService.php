<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'))
            ->withDatabaseUri('https://ran-realtime-default-rtdb.firebaseio.com');

        $this->database = $firebase->createDatabase();
    }

    public function sendNotification($tableId, $user_ids)
    {
        return $this->database
            ->getReference("{$tableId}")
            ->push([
                'user_ids' => $user_ids,
                'timestamp' => now()->timestamp,
            ]);
    }
    public function clearOldNotifications()
    {
        $ref = $this->database->getReference('notifications');

        // Kunin lahat ng notifications
        $notifications = $ref->getValue();

        if (!$notifications) {
            return; // walang data
        }

        $fiveMinutesAgo = now()->subMinutes(5)->timestamp;

        foreach ($notifications as $key => $data) {
            if (isset($data['timestamp']) && $data['timestamp'] <= $fiveMinutesAgo) {
                // Burahin specific child
                $this->database->getReference("notifications/{$key}")->remove();
            }
        }
    }
}
