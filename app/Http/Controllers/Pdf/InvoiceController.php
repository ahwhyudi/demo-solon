<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Debitur;
use App\Models\Invoice;
use App\Models\JobDivisi;
use App\Services\Bank\GetTopBankServis;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function preview($id)
    {
        $job_divisi = JobDivisi::with("finance.user", "formOrder", "debitur")->find($id);
        $formOrder = $job_divisi->formOrder->where("masuk_invoice", 1)->where("status", '!=', "Dibatalkan");
        // dd($formOrder);

        $data = [
            'client' => 'Budi Santoso',
            'client_address' => 'Jl. Mawar No. 21, Surabaya',
            'tanggal' => now(),
            'kode_invoice' => "INV/" . Carbon::now()->format('Y') . "/00" . $job_divisi->id,

            'items' => $formOrder->map(function ($item) {
                return [
                    'nama' => $item->nama,
                    'harga' => $item->harga_jual,
                    'diskon' => $item->diskon,
                    'total' => $item->harga_jual - $item->diskon,
                ];
            })
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data)->setPaper('A4', 'portrait');
        return $pdf->stream('invoice.pdf');
    }

    // public function invPenjual(string $id, string $kategori)
    // {
    //     $job_divisi = JobDivisi::with("penjual", "pembeli", "objek", "debitur")->find($id);
    //     $alamat_objek = $job_divisi->objek->first()->alamat ?? null;

    //     if ($job_divisi->penjual->first()) {
    //         $nama_penjual = $job_divisi->penjual->first()->nama ?? "Belum Ada Data Penjual";
    //     } else {
    //         $nama_penjual = $job_divisi->developer->first()->nama_pt ?? "Belum Ada Data Penjual";
    //     }

    //     if ($job_divisi->pembeli->first()) {
    //         $nama_pembeli = $job_divisi->pembeli->first()->nama ?? "Belum Ada Data Pembeli";
    //     } else {
    //         $nama_pembeli = $job_divisi->debitur->first()->nama ?? "Belum Ada Data Pembeli";
    //     }

    //     $invoicePenjual = Invoice::query()
    //         ->with("detail.formOrder", "finance")
    //         ->where("job_divisi_id", $id)
    //         ->where("kategori", $kategori)
    //         ->first();

    //     $items = $invoicePenjual->detail;

    //     $pembayaran = $invoicePenjual->finance;
    //     $totalCashIn = $pembayaran->where("tipe", "in")->sum("total");

    //     $totalKeseluruhan = $items->pluck('formOrder')->sum('harga_jual');

    //     $kurangLebihBayar = $totalKeseluruhan - $totalCashIn;

    //     $no_faktur = $invoicePenjual->kode ?? null;
    //     $tanggal = $invoicePenjual->created_at->format("d-M-y");

    //     $pdf = Pdf::loadView("pages.print.finance.penjual", [
    //         "alamat_objek" => $alamat_objek,
    //         "nama_penjual" => $nama_penjual,
    //         "nama_pembeli" => $nama_pembeli,
    //         "no_faktur" => $no_faktur,
    //         "tanggal" => $tanggal,
    //         "items" => $items,
    //         "totalKeseluruhan" => $totalKeseluruhan,
    //         "totalCashIn" => $totalCashIn,
    //         "kurangLebihBayar" => $kurangLebihBayar,
    //         "pembayaran" => $pembayaran,
    //         "kategori" => $kategori
    //     ])->setPaper('A4', 'portrait');
    //     return $pdf->stream('invoice_penjual.pdf');
    // }


    public function invPenjual(Request $request, string $id, string $kategori)
    {
        $job_divisi = JobDivisi::with("penjual", "pembeli", "objek", "debitur")->find($id);
        $alamat_objek = $job_divisi->objek->first()->alamat ?? null;

        $nama_penjual = $job_divisi->penjual->first()->nama ?? $job_divisi->developer->first()->nama_pt ?? "Belum Ada Data Penjual";
        $nama_pembeli = $job_divisi->pembeli->first()->nama ?? $job_divisi->debitur->first()->nama ?? "Belum Ada Data Pembeli";

        // Query dinamis: cari berdasarkan ID spesifik (jika cetak ulang riwayat) ATAU ambil versi terbaru
        $invoicePenjual = Invoice::query()
            ->with("detail.formOrder", "finance")
            ->where("job_divisi_id", $id)
            ->where("kategori", $kategori)
            ->when($request->query('invoice_id'), function ($q, $invId) {
                return $q->where('id', $invId);
            })
            ->orderBy("versi", "desc")
            ->first();

        // Tentukan teks watermark
        $versi = $invoicePenjual->versi ?? 1;
        $watermarkText = ($versi == 1) ? "ORIGINAL" : "REVISI KE-" . ($versi - 1);

        $items = $invoicePenjual->detail;
        $pembayaran = $invoicePenjual->finance;
        $totalCashIn = $pembayaran->where("tipe", "in")->sum("total");
        $totalKeseluruhan = $items->pluck('formOrder')->sum('harga_jual');
        $kurangLebihBayar = $totalKeseluruhan - $totalCashIn;

        $pdf = Pdf::loadView("pages.print.finance.penjual", [
            "alamat_objek" => $alamat_objek,
            "nama_penjual" => $nama_penjual,
            "nama_pembeli" => $nama_pembeli,
            "no_faktur" => $invoicePenjual->kode ?? null,
            "tanggal" => $invoicePenjual->created_at->format("d-M-y"),
            "items" => $items,
            "totalKeseluruhan" => $totalKeseluruhan,
            "totalCashIn" => $totalCashIn,
            "kurangLebihBayar" => $kurangLebihBayar,
            "pembayaran" => $pembayaran,
            "kategori" => $kategori,
            "watermark_text" => $watermarkText, // <- Pass ke Blade PDF
            "versi" => $versi
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('invoice_' . $kategori . '_v' . $versi . '.pdf');
    }
    public function printInvoiceBank($job_id, $kategori, GetTopBankServis $getTopBankServis)
    {
        $jobDivisi = JobDivisi::query()
            ->where("id", $job_id)
            ->with("formOrder", "objek")
            ->first();

        $bank = $getTopBankServis->execute($jobDivisi->listBank->first()->bank_id ?? 0)->nama ?? "Bank belum di Input";

        $invoice = Invoice::query()
            ->where("job_divisi_id", $job_id)
            ->where("kategori", $kategori)
            ->first();

        $biayaAkta = $jobDivisi->formOrder->whereIn("kategori", ["notaris", "ppat"]);
        $biayaPengurusan = $jobDivisi->formOrder->whereIn("kategori", ["pnbp_voucher", "operasional"])->values();
        $biayaPajak = $jobDivisi->formOrder->whereIn("kategori", ["pajak"]);
        $totalBiayaNotaris = $biayaAkta->sum("harga_jual") + $biayaPengurusan->sum("harga_jual") + $biayaPajak->sum("harga_jual");

        $namaDebitur = $jobDivisi->debitur->first()->nama ?? null;
        $nilaiTransaksi = $jobDivisi->objek->first()->nilai_transaksi ?? null;
        $nilaiPlafond = $jobDivisi->objek->first()->nilai_plafond ?? null;
        $nilaiHt = $jobDivisi->objek->first()->nilai_ht ?? null;

        $pdf = Pdf::loadView("pages.print.finance.bank", [
            "biayaAkta" => $biayaAkta,
            "biayaPengurusan" => $biayaPengurusan,
            "biayaPajak" => $biayaPajak,
            "jobDivisi" => $jobDivisi,
            "kategori" => $kategori,
            "namaDebitur" => $namaDebitur,
            "nilaiTransaksi" => $nilaiTransaksi,
            "nilaiHt" => $nilaiHt,
            "nilaiPlafond" => $nilaiPlafond,
            "totalBiayaNotaris" => $totalBiayaNotaris,
            "bank" => $bank,
            "invoice" => $invoice
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('invoice_penjual.pdf');
    }

    // public function printInvUmum($id, $kategori)
    // {
    //     $job_divisi = JobDivisi::with("debitur", "pembeli", "objek")->find($id);

    //     $alamat_objek = $job_divisi->objek->first()->alamat ?? null;
    //     $nama_penjual = $job_divisi->penjual->first()->nama ?? null;
    //     $nama_pembeli = $job_divisi->pembeli->first()->nama ?? null;
    //     $nama_customers = $job_divisi->debitur->isNotEmpty()
    //         ? $job_divisi->debitur->pluck('nama')->implode(', ')
    //         : $job_divisi->pembeli->pluck('nama')->implode(', ');

    //     $invoicePenjual = Invoice::query()
    //         ->with("detail.formOrder", "finance")
    //         ->where("job_divisi_id", $id)
    //         ->where("kategori", $kategori)
    //         ->first();

    //     $items = $invoicePenjual->detail;

    //     $pembayaran = $invoicePenjual->finance;
    //     $totalCashIn = $pembayaran->where("tipe", "in")->sum("total");

    //     $totalKeseluruhan = $items->pluck('formOrder')->sum('harga_jual');

    //     $kurangLebihBayar = $totalKeseluruhan - $totalCashIn;

    //     $no_faktur = $invoicePenjual->kode ?? null;
    //     $tanggal = $invoicePenjual->created_at->format("d-M-y");
    //     // dd([
    //     //     'debitur' => $job_divisi->debitur,
    //     //     'pembeli' => $job_divisi->pembeli,
    //     // ]);
    //     // dd(
    //     //     Debitur::withTrashed()
    //     //         ->where('job_divisi_id', 7)
    //     //         ->get(['id', 'nama', 'deleted_at'])
    //     // );

    //     $pdf = Pdf::loadView("pages.print.finance.umum", [
    //         "alamat_objek" => $alamat_objek,
    //         "nama_customers" => $nama_customers,
    //         "nama_penjual" => $nama_penjual,
    //         "nama_pembeli" => $nama_pembeli,
    //         "no_faktur" => $no_faktur,
    //         "tanggal" => $tanggal,
    //         "items" => $items,
    //         "totalKeseluruhan" => $totalKeseluruhan,
    //         "totalCashIn" => $totalCashIn,
    //         "kurangLebihBayar" => $kurangLebihBayar,
    //         "pembayaran" => $pembayaran,
    //         "kategori" => $kategori
    //     ])->setPaper('A4', 'portrait');
    //     return $pdf->stream('invoice_penjual.pdf');
    // }

    public function printInvUmum(Request $request, $id, $kategori)
    {
        $job_divisi = JobDivisi::with("debitur", "pembeli", "objek")->find($id);

        $alamat_objek = $job_divisi->objek->first()->alamat ?? null;
        $nama_penjual = $job_divisi->penjual->first()->nama ?? null;
        $nama_pembeli = $job_divisi->pembeli->first()->nama ?? null;
        $nama_customers = $job_divisi->debitur->isNotEmpty()
            ? $job_divisi->debitur->pluck('nama')->implode(', ')
            : $job_divisi->pembeli->pluck('nama')->implode(', ');

        // Perbaikan: Tambahkan penangkapan invoice_id dari URL dan urutkan berdasarkan versi terbaru
        $invoicePenjual = Invoice::query()
            ->with("detail.formOrder", "finance")
            ->where("job_divisi_id", $id)
            ->where("kategori", $kategori)
            ->when($request->query('invoice_id'), function ($q, $invId) {
                return $q->where('id', $invId);
            })
            ->orderBy("versi", "desc")
            ->first();

        // Tentukan teks watermark berdasarkan kolom versi
        $versi = $invoicePenjual->versi ?? 1;
        $watermarkText = ($versi == 1) ? "ORIGINAL" : "REVISI KE-" . ($versi - 1);

        $items = $invoicePenjual->detail;
        $pembayaran = $invoicePenjual->finance;
        $totalCashIn = $pembayaran->where("tipe", "in")->sum("total");
        $totalKeseluruhan = $items->pluck('formOrder')->sum('harga_jual');
        $kurangLebihBayar = $totalKeseluruhan - $totalCashIn;

        $no_faktur = $invoicePenjual->kode ?? null;
        $tanggal = $invoicePenjual->created_at->format("d-M-y");

        $pdf = Pdf::loadView("pages.print.finance.umum", [
            "alamat_objek" => $alamat_objek,
            "nama_customers" => $nama_customers,
            "nama_penjual" => $nama_penjual,
            "nama_pembeli" => $nama_pembeli,
            "no_faktur" => $no_faktur,
            "tanggal" => $tanggal,
            "items" => $items,
            "totalKeseluruhan" => $totalKeseluruhan,
            "totalCashIn" => $totalCashIn,
            "kurangLebihBayar" => $kurangLebihBayar,
            "pembayaran" => $pembayaran,
            "kategori" => $kategori,
            "watermark_text" => $watermarkText, // <- Diselipkan ke view
            "versi" => $versi
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('invoice_umum_v' . $versi . '.pdf');
    }
}
