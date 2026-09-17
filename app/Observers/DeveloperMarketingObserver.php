<?php

namespace App\Observers;

use App\Models\DeveloperMarketing;
use App\Services\MasterData\DeveloperService;

class DeveloperMarketingObserver
{
    public function created(DeveloperMarketing $marketing): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function updated(DeveloperMarketing $marketing): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function deleted(DeveloperMarketing $marketing): void
    {
        app(DeveloperService::class)->clearCache();
    }
}