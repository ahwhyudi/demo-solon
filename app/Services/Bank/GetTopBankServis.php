<?php

namespace App\Services\Bank;

use App\Models\Bank;

class GetTopBankServis
{
    public function execute(int $bankId)
    {
        $bank = Bank::find($bankId);

        if (!$bank) {
            return null; // atau throw exception sesuai kebutuhan
        }

        if ($bank->parent_id) {
            return $this->upBank($bank->parent_id);
        }

        return $bank;
    }

    private function upBank(int $parentId)
    {
        $bank = Bank::find($parentId);

        if (!$bank) {
            return null;
        }

        if ($bank->parent_id) {
            // PERBAIKAN: Tambahkan 'return' di depan panggilannya
            return $this->upBank($bank->parent_id);
        }

        return $bank;
    }
}
