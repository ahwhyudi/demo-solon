<?php

namespace App\Observers;

use App\Models\Broker;
use App\Services\MasterData\BrokerService;

class BrokerObserver
{
    public function created(Broker $broker): void
    {
        app(BrokerService::class)->clearCache();
    }

    public function updated(Broker $broker): void
    {
        app(BrokerService::class)->clearCache();
    }

    public function deleted(Broker $broker): void
    {
        app(BrokerService::class)->clearCache();
    }

    public function restored(Broker $broker): void
    {
        app(BrokerService::class)->clearCache();
    }

    public function forceDeleted(Broker $broker): void
    {
        app(BrokerService::class)->clearCache();
    }
}