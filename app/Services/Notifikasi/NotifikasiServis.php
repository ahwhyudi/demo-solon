<?php

namespace App\Services\Notifikasi;

use App\Models\Notifikasi;


class NotifikasiServis
{
    public function create(
        int $user,
        string $title,
        string $deskripsi,
        string $link = '#',
        ?int $jobDivisiId = null,
        ?int $formOrderId = null
    ): Notifikasi {

        return Notifikasi::create([
            'user_id' => $user,
            'title' => $title,
            'deskripsi' => $deskripsi,
            'link' => $link,
            'job_divisi_id' => $jobDivisiId,
            'job_divisi_form_order_id' => $formOrderId
        ]);
    }
}
