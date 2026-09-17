<?php

namespace App\Services\MasterData;

use App\Models\Developer;
use Illuminate\Support\Facades\Cache;

class DeveloperService
{
    private const CACHE_KEY = 'master_developer.form';
    private const CACHE_TTL = 86400;

    public function getForForm()
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            fn () => Developer::query()
                ->orderBy('nama_perumahan')
                ->with([
                    'marketing',
                    'legal',
                ])
                ->get()
        );
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}