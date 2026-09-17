<?php

namespace App\Observers;

use App\Models\Bank;
use Illuminate\Support\Facades\Cache;

class BankObserver
{
    private const CACHE_KEY = 'master_bank.form';

    public function created(Bank $bank): void
    {
        $this->clearCache();
    }

    public function updated(Bank $bank): void
    {
        $this->clearCache();
    }

    public function deleted(Bank $bank): void
    {
        $this->clearCache();
    }

    public function restored(Bank $bank): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Bank $bank): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}