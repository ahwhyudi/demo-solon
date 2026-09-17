<?php

namespace App\Observers;

use App\Models\DeveloperLegal;
use App\Services\MasterData\DeveloperService;

class DeveloperLegalObserver
{
    public function created(DeveloperLegal $legal): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function updated(DeveloperLegal $legal): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function deleted(DeveloperLegal $legal): void
    {
        app(DeveloperService::class)->clearCache();
    }
}