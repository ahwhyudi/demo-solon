<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\JobDivisi;
use App\Models\JobDivisiFormOrder;
use App\Models\Lembur;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

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

        $now = Carbon::now();
        [$start_date, $end_date] = match ($groupBy) {
            'week' => [$now->copy()->startOfWeek()->startOfDay(), $now->copy()->endOfWeek()->endOfDay()],
            'month' => [$now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfMonth()->endOfDay()],
            default => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
        };

        $jobDivisi = JobDivisi::query()
            ->withTrashed()
            ->with([
                'formOrder',
                'developer.developer',
                'userOps',
                'pembuat',
                'freeze',
            ])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();

        $formOrder = JobDivisiFormOrder::query()
            ->with(['jobDivisi' => fn ($query) => $query->withTrashed(), 'statusJobOps', 'pnbp'])
            ->whereIn("job_divisi_id", $jobDivisi->pluck("id")->toArray())
            ->get();

        $cuti = Cuti::with('user')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();

        $lembur = Lembur::with('user')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();

        $summary = [
            'job_divisi' => $jobDivisi->count(),
            'pekerjaan' => $formOrder->count(),
            'pendapatan' => $formOrder->sum(fn ($item) => (float) ($item->harga_proses ?? 0)),
            'modal' => $formOrder->sum(fn ($item) => (float) ($item->harga_modal ?? 0)),
            'profit' => $formOrder->sum(fn ($item) => (float) ($item->harga_proses ?? 0) - (float) ($item->harga_modal ?? 0)),
            'selesai' => $jobDivisi->filter(fn ($job) => $this->resolveCompletionStatus($job) === 'selesai')->count(),
            'belum_selesai' => $jobDivisi->filter(fn ($job) => $this->resolveCompletionStatus($job) === 'belum_selesai')->count(),
            'pending' => $jobDivisi->filter(fn ($job) => $this->resolveCompletionStatus($job) === 'pending')->count(),
            'freeze' => $jobDivisi->filter(fn ($job) => $this->resolveCompletionStatus($job) === 'freeze')->count(),
            'hris' => $cuti->count() + $lembur->count(),
        ];

        $trendBuckets = $this->initializeBuckets($start_date, $end_date, $groupBy);

        foreach ($jobDivisi as $job) {
            $bucket = $this->bucketKey(Carbon::parse($job->created_at), $groupBy);

            if (!isset($trendBuckets[$bucket])) {
                continue;
            }

            $status = $this->resolveCompletionStatus($job);
            $trendBuckets[$bucket][$status] += 1;
        }

        foreach ($formOrder as $item) {
            $bucket = $this->bucketKey(Carbon::parse($item->created_at), $groupBy);

            if (!isset($trendBuckets[$bucket])) {
                continue;
            }

            $pendapatan = (float) ($item->harga_proses ?? 0);
            $modal = (float) ($item->harga_modal ?? 0);

            $trendBuckets[$bucket]['pendapatan'] += $pendapatan;
            $trendBuckets[$bucket]['profit'] += $pendapatan - $modal;
        }

        $hrisStatus = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        foreach ($cuti as $item) {
            $status = strtolower((string) ($item->status ?? 'pending'));
            $hrisStatus[$status] = ($hrisStatus[$status] ?? 0) + 1;
        }

        foreach ($lembur as $item) {
            $status = strtolower((string) ($item->status ?? 'pending'));
            $hrisStatus[$status] = ($hrisStatus[$status] ?? 0) + 1;
        }

        $completionRanking = $this->buildCompletionRanking($jobDivisi, $completionGroupBy);
        $menuCompletionChart = $this->buildMenuCompletionChart($formOrder);

        return view('dashboard', [
            "jobDivisi" => $jobDivisi,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "formOrder" => $formOrder,
            "formOrderGroup" => $formOrder->groupBy("kategori"),
            "groupBy" => $groupBy,
            "completionGroupBy" => $completionGroupBy,
            "summary" => $summary,
            "trendChart" => [
                'categories' => array_values(array_column($trendBuckets, 'label')),
                'pendapatan' => array_values(array_column($trendBuckets, 'pendapatan')),
                'profit' => array_values(array_column($trendBuckets, 'profit')),
                'selesai' => array_values(array_column($trendBuckets, 'selesai')),
                'belum_selesai' => array_values(array_column($trendBuckets, 'belum_selesai')),
                'pending' => array_values(array_column($trendBuckets, 'pending')),
                'freeze' => array_values(array_column($trendBuckets, 'freeze')),
            ],
            "hrisChart" => [
                'categories' => ['Cuti', 'Lembur'],
                'totals' => [$cuti->count(), $lembur->count()],
                'status' => [
                    $hrisStatus['pending'] ?? 0,
                    $hrisStatus['approved'] ?? 0,
                    $hrisStatus['rejected'] ?? 0,
                ],
            ],
            "completionChart" => $completionRanking,
            "menuCompletionChart" => $menuCompletionChart,
        ]);
    }

    private function initializeBuckets(Carbon $startDate, Carbon $endDate, string $groupBy): array
    {
        $buckets = [];
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $key = $this->bucketKey($cursor, $groupBy);

            $buckets[$key] = [
                'label' => $this->bucketLabel($cursor, $groupBy),
                'pendapatan' => 0,
                'profit' => 0,
                'selesai' => 0,
                'belum_selesai' => 0,
                'pending' => 0,
                'freeze' => 0,
            ];

            $cursor = match ($groupBy) {
                'week' => $cursor->copy()->addWeek()->startOfWeek(),
                'month' => $cursor->copy()->addMonth()->startOfMonth(),
                default => $cursor->copy()->addDay()->startOfDay(),
            };
        }

        return $buckets;
    }

    private function bucketKey(Carbon $date, string $groupBy): string
    {
        return match ($groupBy) {
            'week' => $date->copy()->startOfWeek()->format('Y-m-d'),
            'month' => $date->format('Y-m'),
            default => $date->format('Y-m-d'),
        };
    }

    private function bucketLabel(Carbon $date, string $groupBy): string
    {
        return match ($groupBy) {
            'week' => 'Minggu ' . $date->copy()->startOfWeek()->format('d M'),
            'month' => $date->translatedFormat('M Y'),
            default => $date->translatedFormat('d M'),
        };
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
            ->sortByDesc(fn ($item) => $item['selesai'] + $item['belum_selesai'] + $item['pending'] + $item['freeze'])
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
