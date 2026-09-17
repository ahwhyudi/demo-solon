<?php

namespace App\Services\MasterData;

use App\Models\Bank;
use Illuminate\Support\Facades\Cache;

class BankService
{
    private const CACHE_KEY = 'master_bank.form';

    private const CACHE_TTL = 86400;

    public function getForForm()
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            function () {
                return Bank::query()
                    ->orderBy('nama')
                    ->with([
                        'kepalaLegal',
                        'legal',
                        'kepalaMarketing',
                        'marketing',
                    ])
                    ->get();
            }
        );
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}