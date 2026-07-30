<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\JobDivisiFinance;
use App\Models\JobDivisiFormOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FinanceReportController extends Controller
{
    public function jurnal(Request $request)
    {
        $report = $this->buildReport($request);

        return view('pages.Finance.reports.jurnal', $report);
    }

    public function neraca(Request $request)
    {
        $report = $this->buildReport($request);

        return view('pages.Finance.reports.neraca', $report);
    }

    public function labaKotor(Request $request)
    {
        $report = $this->buildReport($request);

        return view('pages.Finance.reports.laba-kotor', $report);
    }

    public function labaBersih(Request $request)
    {
        $report = $this->buildReport($request);

        return view('pages.Finance.reports.laba-bersih', $report);
    }

    protected function buildReport(Request $request): array
    {
        $month = $request->get('month', now()->format('Y-m'));

        try {
            $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable $th) {
            $period = now()->startOfMonth();
            $month = $period->format('Y-m');
        }

        $startDate = $period->copy()->startOfMonth();
        $endDate = $period->copy()->endOfMonth();

        $approvedTransactions = JobDivisiFinance::query()
            ->with(['jobDivisi', 'formOrder', 'user'])
            ->whereHas('jobDivisi')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where(function ($query) {
                $query->whereRaw('LOWER(status) = ?', ['disetujui'])
                    ->orWhereRaw('LOWER(status) = ?', ['approved']);
            })
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $approvedFormOrders = JobDivisiFormOrder::query()
            ->with(['jobDivisi', 'finance'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('jobDivisi')
            ->get()
            ->filter(function (JobDivisiFormOrder $item) {
                return (float) $item->harga_proses > 0 || (float) $item->harga_modal > 0;
            })
            ->values();

        $jurnalItems = $approvedTransactions->map(function (JobDivisiFinance $item) {
            $accountDebit = $item->tipe === 'in' ? 'Kas & Bank' : $this->resolveExpenseAccount($item);
            $accountCredit = $item->tipe === 'in' ? 'Pendapatan Jasa' : 'Kas & Bank';

            return [
                'tanggal' => $item->tanggal ? Carbon::parse($item->tanggal)->format('d M Y H:i') : '-',
                'kode' => $item->jobDivisi->kode ?? '-',
                'debit_account' => $accountDebit,
                'credit_account' => $accountCredit,
                'debit' => $item->tipe === 'in' ? (float) $item->total : 0,
                'credit' => $item->tipe === 'out' ? (float) $item->total : 0,
                'nominal' => (float) $item->total,
                'peruntukan' => $item->peruntukan ?? '-',
                'keterangan' => $item->keterangan ?? '-',
                'tipe' => strtoupper((string) $item->tipe),
                'user' => $item->user->name ?? '-',
            ];
        })->values();

        $revenueIn = (float) $approvedTransactions->where('tipe', 'in')->sum('total');
        $expenseOut = (float) $approvedTransactions->where('tipe', 'out')->sum('total');
        $saldoKas = $revenueIn - $expenseOut;

        $grossProfitItems = $approvedFormOrders->map(function (JobDivisiFormOrder $item) {

            $pendapatan = (float) $item->harga_proses;
            $modal = (float) $item->harga_modal;
            $labaKotor = $pendapatan - $modal;

            return [
                'tanggal' => $item->created_at ? Carbon::parse($item->created_at)->format('d M Y H:i') : '-',
                'kode' => $item->jobDivisi->kode ?? '-',
                'item' => $item->nama ?? '-',
                'pendapatan' => $pendapatan,
                'modal' => $modal,
                'laba_kotor' => $labaKotor,
                'status' => $item->status ?? '-',
            ];
        })->values();

        $topProfit = $grossProfitItems
            ->sortByDesc('laba_kotor')
            ->take(5)
            ->values();

        $recentTransactions = $approvedTransactions
            ->sortByDesc('tanggal')
            ->take(8)
            ->values();

        $totalPendapatan = (float) $grossProfitItems->sum('pendapatan');
        $totalModal = (float) $grossProfitItems->sum('modal');
        $labaKotor = (float) $grossProfitItems->sum('laba_kotor');
        $bebanOperasional = $expenseOut;
        $labaBersih = $labaKotor - $bebanOperasional;
        $estimasiPiutang = max($totalPendapatan - $revenueIn, 0);

        $expenseBreakdown = $approvedTransactions
            ->where('tipe', 'out')
            ->groupBy(function (JobDivisiFinance $item) {
                return $item->peruntukan ?: 'Lainnya';
            })
            ->map(fn(Collection $group) => (float) $group->sum('total'))
            ->sortDesc();

        $incomeBreakdown = $approvedTransactions
            ->where('tipe', 'in')
            ->groupBy(function (JobDivisiFinance $item) {
                return $item->peruntukan ?: 'Pendapatan Lainnya';
            })
            ->map(fn(Collection $group) => (float) $group->sum('total'))
            ->sortDesc();

        $totalAktiva = $saldoKas + $estimasiPiutang;

        $totalEkuitas = $totalModal + $labaBersih;


        $assets = collect([
            [
                'nama' => 'Kas & Bank',
                'nominal' => $saldoKas,
                'icon' => 'bi-bank',
                'color' => 'primary',
            ],
            [
                'nama' => 'Piutang',
                'nominal' => $estimasiPiutang,
                'icon' => 'bi-wallet2',
                'color' => 'warning',
            ],
        ]);

        $equity = collect([
            [
                'nama' => 'Modal',
                'nominal' => $totalModal,
                'icon' => 'bi-cash-stack',
                'color' => 'info',
            ],
            [
                'nama' => 'Laba Bersih',
                'nominal' => $labaBersih,
                'icon' => 'bi-graph-up-arrow',
                'color' => $labaBersih >= 0 ? 'success' : 'danger',
            ],
        ]);
        $monthlyCashflow = [];

        for ($i = 1; $i <= 12; $i++) {

            $monthlyCashflow[] = [

                'bulan' => Carbon::create()
                    ->month($i)
                    ->translatedFormat('M'),

                'masuk' => (float) JobDivisiFinance::query()

                    ->whereYear('tanggal', $period->year)

                    ->whereMonth('tanggal', $i)

                    ->where('tipe', 'in')

                    ->where(function ($q) {
                        $q->whereRaw('LOWER(status)=?', ['approved'])
                            ->orWhereRaw('LOWER(status)=?', ['disetujui']);
                    })

                    ->sum('total'),

                'keluar' => (float) JobDivisiFinance::query()

                    ->whereYear('tanggal', $period->year)

                    ->whereMonth('tanggal', $i)

                    ->where('tipe', 'out')

                    ->where(function ($q) {
                        $q->whereRaw('LOWER(status)=?', ['approved'])
                            ->orWhereRaw('LOWER(status)=?', ['disetujui']);
                    })

                    ->sum('total'),

            ];
        }
        $topProfit = $grossProfitItems
            ->sortByDesc('laba_kotor')
            ->take(5)
            ->values();

        $expenseChartLabels = $expenseBreakdown
            ->keys()
            ->values();

        $expenseChartSeries = $expenseBreakdown
            ->values();

        $incomeChartLabels = $incomeBreakdown
            ->keys()
            ->values();

        $incomeChartSeries = $incomeBreakdown
            ->values();

        $cashflowLabels = collect($monthlyCashflow)
            ->pluck('bulan');

        $cashflowMasuk = collect($monthlyCashflow)
            ->pluck('masuk');

        $cashflowKeluar = collect($monthlyCashflow)
            ->pluck('keluar');


        return [
            'activeFinanceMenu' => 'reports',
            'month' => $month,
            'periodLabel' => $period->translatedFormat('F Y'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => [
                'kas_masuk' => $revenueIn,
                'kas_keluar' => $expenseOut,
                'saldo_kas' => $saldoKas,
                'pendapatan' => $totalPendapatan,
                'modal' => $totalModal,
                'piutang' => $estimasiPiutang,
                'laba_kotor' => $labaKotor,
                'beban_operasional' => $bebanOperasional,
                'laba_bersih' => $labaBersih,
                'jumlah_transaksi' => $approvedTransactions->count(),
                'jumlah_item' => $grossProfitItems->count(),
            ],
            'jurnalItems' => $jurnalItems,
            'grossProfitItems' => $grossProfitItems,
            'expenseBreakdown' => $expenseBreakdown,
            'incomeBreakdown' => $incomeBreakdown,
            'assets' => $assets,
            'equity' => $equity,
            'monthlyCashflow' => $monthlyCashflow,
            'totalAktiva' => $totalAktiva,
            'totalEkuitas' => $totalEkuitas,
            'topProfit' => $topProfit,
            'expenseChartLabels' => $expenseChartLabels,

            'expenseChartSeries' => $expenseChartSeries,

            'incomeChartLabels' => $incomeChartLabels,

            'incomeChartSeries' => $incomeChartSeries,

            'cashflowLabels' => $cashflowLabels,

            'cashflowMasuk' => $cashflowMasuk,

            'cashflowKeluar' => $cashflowKeluar,
            'recentTransactions' => $recentTransactions,
        ];
    }

    protected function resolveExpenseAccount(JobDivisiFinance $item): string
    {
        $peruntukan = strtolower((string) $item->peruntukan);

        if (str_contains($peruntukan, 'pajak')) {
            return 'Beban Pajak';
        }

        if (str_contains($peruntukan, 'pnbp')) {
            return 'Beban PNBP';
        }

        if (str_contains($peruntukan, 'notaris') || str_contains($peruntukan, 'ppat')) {
            return 'Beban Jasa Notaris';
        }

        return 'Beban Operasional';
    }
}
