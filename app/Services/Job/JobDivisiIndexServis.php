<?php

namespace App\Services\Job;

use App\Models\JobDivisi;
use App\Models\MasterDataFormOrder;
use Illuminate\Http\Request;

class JobDivisiIndexServis
{
    public function execute(Request $request, array $options = []): array
    {
        $completedOnly = (bool) ($options["completed_only"] ?? false);
        $kode = $request->kode ?? $request->q;
        $status = $completedOnly ? null : $request->status;
        $tanggalAkad = $request->tanggal_akad;
        $namaPenghadap = $request->nama_penghadap;

        $countFilter = collect([
            'kode' => $kode,
            'status' => $status,
            'tanggal_akad' => $tanggalAkad,
            'nama_penghadap' => $namaPenghadap,
        ])
            ->filter(fn($value) => filled($value))
            ->count();

        $items = JobDivisi::with(
            "bank",
            "listBank",
            "objek",
            "divisiYangDituju",
            "jenisAkad",
            "debitur",
            "pembatalan.user",
            "pembatalan.user2",
            "formOrder",
            "finance"
        )
            ->orderBy("is_pending", "desc")
            ->orderBy("id", "desc")
            ->when($kode, function ($query) use ($kode) {
                return $query->where("kode", "like", "%$kode%");
            })
            ->when($status, function ($query) use ($status) {
                if ($status === "Pending") {
                    return $query->where("is_pending", 1);
                }

                return $query->where("status", $status)
                    ->where("is_pending", 0);
            })
            ->when($tanggalAkad, function ($query) use ($tanggalAkad) {
                return $query->where(function ($query) use ($tanggalAkad) {
                    $query->whereDate("tanggal_akad", $tanggalAkad)
                        ->orWhere(function ($query) use ($tanggalAkad) {
                            $query->whereNull("tanggal_akad")
                                ->whereDate("tanggal_rencana_akad", $tanggalAkad);
                        });
                });
            })
            ->when($namaPenghadap, function ($query) use ($namaPenghadap) {
                return $query->whereHas("debitur", function ($query) use ($namaPenghadap) {
                    return $query->where("nama", "LIKE", "%$namaPenghadap%");
                });
            })
            ->when($completedOnly, function ($query) {
                return $query->where("status", "Selesai")
                    ->where("is_pending", 0);
            }, function ($query) {
                return $query->where("status", "!=", "Selesai");
            })
            ->paginate(12)
            ->withQueryString();

        return [
            "items" => $items,
            "filter_active" => $countFilter > 0,
            "masterPekerjaan" => MasterDataFormOrder::query()->get(),
            "countFilter" => $countFilter,
            "statusOptions" => $completedOnly ? ["Selesai"] : ["Pra Akad", "Akad", "Pending", "Batal Akad", "Selesai"],
        ];
    }
}
