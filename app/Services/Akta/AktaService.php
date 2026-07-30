<?php


namespace App\Services\Akta;

use App\Models\JobDivisiFormOrder;
use App\Models\StatusJobOps;
use App\Models\User;
use Illuminate\Http\Request;

class AktaService
{
    public function getIndexData(Request $request, ?string $tipe = null): array
    {
        $tipe = $tipe ?: 'notaris';

        return [
            "items" => $this->getItems($request, $tipe),
            "user_ops" => $this->getUserOps(),
            "tipe" => $tipe,
            "countFilter" => $this->countActiveFilters($request),
            "prosesOptions" => $this->getProsesOptions($tipe),
            "statusOptions" => $this->getStatusOptions($tipe),
            "bankOptions" => \App\Models\Bank::orderBy('nama')->get(),
        ];
    }

    private function countActiveFilters(Request $request): int
    {
        $filterKeys = [
            'parent',
            'nomor_akta',
            'proses',
            'status',
            'penugasan',
            'penugasan_qc',
            'nomor_objek',
            'nama_debitur',
            'bank',
            'status_akad',
        ];

        return collect($request->only($filterKeys))
            ->filter(fn($value) => filled($value))
            ->count();
    }

    private function getWorkflow(string $tipe): array
    {
        $tipeConfig = in_array($tipe, ['notaris', 'ppat'])
            ? 'notaris'
            : $tipe;

        $client = config('app.notaris', 'default');

        return config("workflow.akta.$client.$tipeConfig")
            ?? config("workflow.akta.default.$tipeConfig")
            ?? [];
    }

    private function getStatusOptions(string $tipe)
    {
        $workflow = $this->getWorkflow($tipe);

        $existingStatusOptions = StatusJobOps::query()
            ->join('job_divisi_form_orders', 'status_job_ops.job_divisi_form_order_id', '=', 'job_divisi_form_orders.id')
            ->where('job_divisi_form_orders.kategori', $tipe)
            ->whereNotNull('status_job_ops.status')
            ->select('status_job_ops.status')
            ->distinct()
            ->orderBy('status_job_ops.status')
            ->pluck('status_job_ops.status');

        return collect($workflow)
            ->pluck('name')
            ->merge($existingStatusOptions)
            ->prepend('Belum diproses')
            ->push('Perlu Perbaikan')
            ->unique()
            ->values();
    }

    private function getProsesOptions(string $tipe)
    {
        return JobDivisiFormOrder::query()
            ->whereNotIn("status", ["rejected", "Dibatalkan", "pending"])
            ->where('kategori', $tipe)
            ->whereHas("jobDivisi")
            ->select('nama')
            ->distinct()
            ->orderBy('nama')
            ->pluck('nama');
    }

    private function getItems(Request $request, string $tipe)
    {
        return JobDivisiFormOrder::with([
            'jobDivisi.jenisAkad',
            'jobDivisi.listBank',
            'jobDivisi.debitur',
            'jobDivisi.objek',
            'statusJobOps.createdBy',
            'statusJobOps.user',
            'statusJobOps.nextUser',
            'nomorPpat',
        ])
            ->whereNotIn("status", ["rejected", "Dibatalkan", "pending"])
            ->whereIn('kategori', [
                'notaris',
                'ppat',
                'legalisasi',
                'waarmerking',
                'surat-keluar',
                'wasiat',
            ])
            ->where('kategori', $tipe)
            ->whereHas("jobDivisi")
            ->orderBy("id", "desc")
            ->when($request->parent, function ($query, $parent) {
                return $query->whereHas('jobDivisi', function ($query) use ($parent) {
                    return $query->where('kode', 'LIKE', "%$parent%");
                });
            })
            ->when($request->nomor_akta, function ($query, $nomorAkta) {
                return $query->whereHas('nomorPpat', function ($query) use ($nomorAkta) {
                    return $query->where('nomor', 'LIKE', "%$nomorAkta%");
                });
            })
            ->when($request->proses, function ($query, $proses) {
                return $query->where('nama', $proses);
            })
            ->when($request->status, function ($query, $status) {
                return $this->applyStatusFilter($query, $status);
            })
            ->when($request->penugasan, function ($query, $penugasan) {
                return $query->whereHas('statusJobOps', function ($q) use ($penugasan) {
                    $q->where('user_id', $penugasan)
                        ->whereRaw('status_job_ops.id = (
                select max(s2.id)
                from status_job_ops s2
                where s2.job_divisi_form_order_id = job_divisi_form_orders.id
            )');
                });
            })
            ->when($request->penugasan_qc, function ($query, $penugasanQc) {
                return $query->whereHas('statusJobOps', function ($q) use ($penugasanQc) {
                    $q->where('next_user', $penugasanQc)
                        ->whereRaw('status_job_ops.id = (
                select max(s2.id)
                from status_job_ops s2
                where s2.job_divisi_form_order_id = job_divisi_form_orders.id
            )');
                });
            })

            ->when($request->nomor_objek, function ($query, $nomorObjek) {
                return $query->whereHas('jobDivisi.objek', function ($q) use ($nomorObjek) {
                    $q->where('no_sertifikat', 'LIKE', "%{$nomorObjek}%");
                });
            })

            ->when($request->nama_debitur, function ($query, $namaDebitur) {
                return $query->whereHas('jobDivisi.debitur', function ($q) use ($namaDebitur) {
                    $q->where('nama', 'LIKE', "%{$namaDebitur}%");
                });
            })

            ->when($request->bank, function ($query, $bank) {
                return $query->whereHas('jobDivisi.listBank', function ($q) use ($bank) {
                    $q->where('banks.id', $bank);
                });
            })

            ->when($request->status_akad, function ($query, $statusAkad) {
                return $query->whereHas('jobDivisi', function ($q) use ($statusAkad) {
                    $q->where('status', $statusAkad);
                });
            })
            ->paginate(10)
            ->withQueryString();
    }

    private function applyStatusFilter($query, string $status)
    {
        if ($status === 'Belum diproses') {
            return $query->doesntHave('statusJobOps');
        }

        if ($status === 'Perlu Perbaikan') {
            return $query->whereHas('statusJobOps', function ($query) {
                return $query->whereNotNull('status_penolakan')
                    ->whereRaw('status_job_ops.id = (
                        select max(status_job_ops_latest.id)
                        from status_job_ops as status_job_ops_latest
                        where status_job_ops_latest.job_divisi_form_order_id = job_divisi_form_orders.id
                    )');
            });
        }

        return $query->whereHas('statusJobOps', function ($query) use ($status) {
            return $query->where('status', $status)
                ->whereNull('status_penolakan')
                ->whereRaw('status_job_ops.id = (
                    select max(status_job_ops_latest.id)
                    from status_job_ops as status_job_ops_latest
                    where status_job_ops_latest.job_divisi_form_order_id = job_divisi_form_orders.id
                )');
        });
    }

    private function getUserOps()
    {
        return User::orderBy('name', 'asc')
            ->get()
            ->map(function ($user) {
                return [
                    'value' => (string) $user->id,
                    'label' => $user->name,
                ];
            });
    }
}
