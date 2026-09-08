<?php

namespace App\Http\Controllers;

use App\Models\ApprovalFreez;
use App\Models\Cuti;
use App\Models\JobDivisi;
use App\Models\JobDivisiFormOrder;
use App\Models\Lembur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $groupBy = $request->get('group_by', 'day');
        if (!in_array($groupBy, ['day', 'week', 'month'])) {
            $groupBy = 'day';
        }

        $completionGroupBy = $request->get('completion_group_by', 'perusahaan');
        if (!in_array($completionGroupBy, ['perusahaan', 'pegawai'])) {
            $completionGroupBy = 'perusahaan';
        }

        // Ambil filter bulan dari request (format: "YYYY-MM"), default bulan saat ini
        $filterMonth = $request->get('filter_month', date('Y-m'));
        $start_date = Carbon::createFromFormat('Y-m', $filterMonth)->startOfMonth()->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m', $filterMonth)->endOfMonth()->endOfDay();

        // 1. Berkas Masuk dihitung berdasarkan created_at dalam rentang bulan tersebut
        $berkasMasukCount = JobDivisi::query()
            ->count();

        // 2. Data utama & laporan status lainnya dihitung berdasarkan updated_at dalam rentang bulan tersebut
        $jobDivisi = JobDivisi::query()
            ->with([
                'formOrder',
                'developer.developer',
                'userOps',
                'pembuat',
                'freeze',
            ])
            ->get();
        // dd($jobDivisi);

        $formOrder = JobDivisiFormOrder::query()
            ->with(['jobDivisi' => fn($query) => $query->withTrashed(), 'statusJobOps', 'pnbp'])
            ->whereIn("job_divisi_id", $jobDivisi->pluck("id")->toArray())
            ->get();

        // Inisialisasi penghitungan status laporan
        $berkasBerjalan = 0;
        $berkasSelesai = 0;
        $berkasPending = 0;
        // $berkasFreeze = 0;

        $berkasFreeze = ApprovalFreez::query()
            ->whereIn("job_divisi", $jobDivisi->pluck("id")->toArray())
            ->where('status', 'Disetujui')
            ->where(function ($query) {
                $query->whereNull("end_date")
                    ->orWhere("end_date", ">", now());
            })
            ->count();

        foreach ($jobDivisi as $job) {

            $statusType = $job->status;

            if ($statusType === 'Pending') {
                $berkasPending++;
            } elseif ($statusType === 'Selesai') {

                $berkasSelesai++;
                // dd($berkasSelesai);
            } elseif (in_array($statusType, ['Pra Akad', 'Akad',"Batal Akad"])) {

                $berkasBerjalan++;
            }
        }

        $summary = [
            'berkas_masuk' => $berkasMasukCount,
            'berkas_berjalan' => $berkasBerjalan,
            'berkas_selesai' => $berkasSelesai,
            'berkas_pending' => $berkasPending,
            'berkas_freeze' => $berkasFreeze,
            'pekerjaan' => $formOrder->count(),
            'pendapatan' => $formOrder->sum(fn($item) => (float) ($item->harga_proses ?? 0)),
            'modal' => $formOrder->sum(fn($item) => (float) ($item->harga_modal ?? 0)),
            'profit' => $formOrder->sum(fn($item) => (float) ($item->harga_proses ?? 0) - (float) ($item->harga_modal ?? 0)),
        ];

        $completionRanking = $this->buildCompletionRanking($jobDivisi, $completionGroupBy);
        $menuCompletionChart = $this->buildMenuCompletionChart($formOrder);

        return view('dashboard', [
            "jobDivisi" => $jobDivisi,
            "formOrder" => $formOrder,
            "formOrderGroup" => $formOrder->groupBy("kategori"),
            "groupBy" => $groupBy,
            "completionGroupBy" => $completionGroupBy,
            "summary" => $summary,
            "filter_month" => $filterMonth,
            "completionChart" => $completionRanking,
            "menuCompletionChart" => $menuCompletionChart,
        ]);
    }

    private function buildCompletionRanking(Collection $jobDivisi, string $completionGroupBy): array
    {
        $ranking = [];

        foreach ($jobDivisi as $job) {
            if ($completionGroupBy === 'pegawai') {
                $label = $job->userOps->name ?? $job->pembuat->name ?? 'Tanpa Pegawai';
                $this->incrementCompletionRanking($ranking, $label, $job);
                continue;
            }

            if ($job->developer->isEmpty()) {
                $this->incrementCompletionRanking($ranking, 'Tanpa Perusahaan', $job);
                continue;
            }

            foreach ($job->developer as $developer) {
                $label = $developer->developer->nama_pt
                    ?? $developer->developer->nama_perumahan
                    ?? 'Tanpa Perusahaan';

                $this->incrementCompletionRanking($ranking, $label, $job);
            }
        }

        $ranking = collect($ranking)
            ->sortByDesc(fn($item) => $item['selesai'] + $item['belum_selesai'] + $item['pending'] + $item['freeze'])
            ->take(10)
            ->values();

        return [
            'labels' => $ranking->pluck('label')->all(),
            'selesai' => $ranking->pluck('selesai')->all(),
            'belum_selesai' => $ranking->pluck('belum_selesai')->all(),
            'pending' => $ranking->pluck('pending')->all(),
            'freeze' => $ranking->pluck('freeze')->all(),
        ];
    }

    private function buildMenuCompletionChart(Collection $formOrder): array
    {
        $menuMap = [
            'notaris' => 'Notaris',
            'ppat' => 'PPAT',
            'operasional' => 'Operasional',
            'pajak' => 'Pajak',
            'pnbp_voucher' => 'PNBP',
        ];

        $chart = collect($menuMap)->mapWithKeys(function ($label, $key) {
            return [
                $key => [
                    'label' => $label,
                    'selesai' => 0,
                    'belum_selesai' => 0,
                    'pending' => 0,
                    'freeze' => 0,
                ]
            ];
        })->all();

        foreach ($formOrder as $item) {
            if (!isset($menuMap[$item->kategori])) {
                continue;
            }

            $status = $this->resolveMenuStatus($item);
            $chart[$item->kategori][$status]++;
        }

        return [
            'labels' => array_values(array_column($chart, 'label')),
            'selesai' => array_values(array_column($chart, 'selesai')),
            'belum_selesai' => array_values(array_column($chart, 'belum_selesai')),
            'pending' => array_values(array_column($chart, 'pending')),
            'freeze' => array_values(array_column($chart, 'freeze')),
        ];
    }

    private function incrementCompletionRanking(array &$ranking, string $label, JobDivisi $job): void
    {
        if (!isset($ranking[$label])) {
            $ranking[$label] = [
                'label' => $label,
                'selesai' => 0,
                'belum_selesai' => 0,
                'pending' => 0,
                'freeze' => 0,
            ];
        }

        $ranking[$label][$this->resolveCompletionStatus($job)]++;
    }

    private function resolveCompletionStatus(JobDivisi $job): string
    {
        $freeze = $job->freeze;
        $isFreezeActive = $job->trashed()
            || ($freeze && $freeze->status === 'Disetujui' && empty($freeze->end_date));

        if ($isFreezeActive) {
            return 'freeze';
        }

        if ((int) ($job->is_pending ?? 0) === 1) {
            return 'pending';
        }

        if ($job->status === 'Selesai') {
            return 'selesai';
        }

        return 'belum_selesai';
    }

    private function resolveMenuStatus(JobDivisiFormOrder $item): string
    {
        $job = $item->jobDivisi;

        if ($job && $this->resolveCompletionStatus($job) === 'freeze') {
            return 'freeze';
        }

        if ($job && $this->resolveCompletionStatus($job) === 'pending') {
            return 'pending';
        }

        if ($item->kategori === 'pnbp_voucher') {
            return ($item->pnbp?->status ?? null) === 'Disetujui' ? 'selesai' : 'belum_selesai';
        }

        $lastStatus = $item->statusJobOps->last()->status ?? null;

        return $lastStatus === 'Selesai' ? 'selesai' : 'belum_selesai';
    }
}
