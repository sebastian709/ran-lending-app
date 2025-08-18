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
}
