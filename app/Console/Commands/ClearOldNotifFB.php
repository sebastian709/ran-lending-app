<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;

class ClearOldNotifFB extends Command
{
    protected $signature = 'notifications:clear-old';
    protected $description = 'Delete notifications older than 5 minutes from Firebase';

    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        parent::__construct();
        $this->firebase = $firebase;
    }

    public function handle()
    {
        $this->firebase->clearOldNotifications();
        $this->info('✅ Old notifications cleared at ' . now());
    }
}
