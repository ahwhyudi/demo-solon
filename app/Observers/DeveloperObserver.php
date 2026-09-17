<?php

namespace App\Observers;

use App\Models\Developer;
use App\Services\MasterData\DeveloperService;

class DeveloperObserver
{
    public function created(Developer $developer): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function updated(Developer $developer): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function deleted(Developer $developer): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function restored(Developer $developer): void
    {
        app(DeveloperService::class)->clearCache();
    }

    public function forceDeleted(Developer $developer): void
    {
        app(DeveloperService::class)->clearCache();
    }
}