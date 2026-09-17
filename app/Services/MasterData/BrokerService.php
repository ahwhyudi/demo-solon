<?php

namespace App\Services\MasterData;

use App\Models\Broker;
use Illuminate\Support\Facades\Cache;

class BrokerService
{
    private const CACHE_KEY = 'master_broker.form';
    private const CACHE_TTL = 86400;

    public function getForForm()
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            fn () => Broker::query()
                ->orderBy('nama_pt')
                ->with('marketing')
                ->get()
        );
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}