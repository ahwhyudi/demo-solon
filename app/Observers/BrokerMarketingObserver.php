<?php

namespace App\Observers;

use App\Models\BrokerMarketing;
use App\Services\MasterData\BrokerService;

class BrokerMarketingObserver
{
    public function created(BrokerMarketing $marketing): void
    {
        app(BrokerService::class)->clearCache();
    }

    public function updated(BrokerMarketing $marketing): void
    {
        app(BrokerService::class)->clearCache();
    }

    public function deleted(BrokerMarketing $marketing): void
    {
        app(BrokerService::class)->clearCache();
    }
}