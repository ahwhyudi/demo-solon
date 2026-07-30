<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\JobDivisi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $jobDivisi = JobDivisi::with(
    //         "invoice",
    //         "penjual",
    //         "pembeli",
    //         "debitur",
    //         "developer"
    //     )
    //         ->find($request->job_divisi_id);

    //     if ($request->kategori == "penjual" && $jobDivisi->penjual->isEmpty() && $jobDivisi->developer->isEmpty()) {
    //         return redirect()->back()->with("error", "Data penjual belum lengkap");
    //     }

    //     if ($request->kategori === "pembeli" && $jobDivisi->pembeli->isEmpty() && $jobDivisi->debitur->isEmpty()) {
    //         return redirect()->back()->with("error", "Data pembeli atau debitur belum lengkap");
    //     }

    //     DB::beginTransaction();
    //     try {

    //         $invoiceJob = $jobDivisi->invoice;

    //         $kode = "$jobDivisi->kode/0";
    //         $cekKodeInv = Invoice::query()
    //             ->where("job_divisi_id", $jobDivisi->id)
    //             ->orderBy("id", "desc")
    //             ->first()->kode ?? null;

    //         if ($cekKodeInv) {
    //             $kode = $cekKodeInv;
    //         }

    //         $formDataInvoice = [
    //             "job_divisi_id" => $request->job_divisi_id,
    //             "kategori" => $request->kategori,
    //             "kode" => ++$kode,
    //             "created_by" => Auth::user()->id
    //         ];

    //         // jika kategori invoice sudah ada update itemnya
    //         if ($invoiceJob->where("kategori", $request->kategori)->first()) {
    //             $invoiceDetail = InvoiceDetail::query()
    //                 ->where("invoice_id", $invoiceJob->where("kategori", $request->kategori)->first()->id)
    //                 ->delete();
    //             $invoice = Invoice::where("kategori", $request->kategori)->first();
    //         } else {
    //             $invoice = Invoice::create($formDataInvoice);
    //         }


    //         $formDetailInv = collect($request->input("item_print_inv", []))->map(function ($item, $index) use ($invoice) {
    //             return [
    //                 "invoice_id" => $invoice->id,
    //                 "job_divisi_form_order_id" => $item,
    //                 "created_at" => now(),
    //                 "updated_at" => now()
    //             ];
    //         });

    //         if (!empty($formDetailInv)) {
    //             $insertDetailInv = InvoiceDetail::insert($formDetailInv->toArray());
    //         }


    //         DB::commit();

    //         if ($request->kategori === "bank") {
    //             return redirect()->route("pdf.invoice-bank.print", ["id" => $jobDivisi->id, "kategori" => $request->kategori]);
    //         } elseif ($request->kategori === "penjual" || $request->kategori === "pembeli") {
    //             return redirect()->route("pdf.invoice.print", ["id" => $jobDivisi->id, "kategori" => $request->kategori]);
    //         }

    //         return redirect()->back()->with("success", "Berhasil print invoice");
    //     } catch (Exception $th) {
    //         DB::rollBack();

    //         return redirect()->back()->with("error", "Gagal print invoice, coba beberapa saat lagi");
    //     }
    // }

    public function store(Request $request)
    {
        $jobDivisi = JobDivisi::with(
            "invoice",
            "penjual",
            "pembeli",
            "debitur",
            "developer"
        )->find($request->job_divisi_id);

        if ($request->kategori == "penjual" && $jobDivisi->penjual->isEmpty() && $jobDivisi->developer->isEmpty()) {
            return redirect()->back()->with("error", "Data penjual belum lengkap");
        }

        if ($request->kategori === "pembeli" && $jobDivisi->pembeli->isEmpty() && $jobDivisi->debitur->isEmpty()) {
            return redirect()->back()->with("error", "Data pembeli atau debitur belum lengkap");
        }

        DB::beginTransaction();
        try {
            $invoiceJob = $jobDivisi->invoice;

            // Cari invoice terakhir di kategori yang sama untuk menentukan versi & kode berikutnya
            $lastInvoice = Invoice::query()
                ->where("job_divisi_id", $jobDivisi->id)
                ->where("kategori", $request->kategori)
                ->orderBy("versi", "desc")
                ->first();

            $nextVersi = $lastInvoice ? $lastInvoice->versi + 1 : 1;

            // Format penomoran kode: jika cetakan ke-2 (revisi 1), kodenya ditambah penanda versi
            if ($lastInvoice) {
                // Contoh: INV/2026/001-V2
                $baseKode = preg_replace('/-V\d+$/', '', $lastInvoice->kode);
                $kode = $baseKode . "-V" . $nextVersi;
            } else {
                // Kode awal cetakan pertama
                $kode = "INV/" . now()->format('Y') . "/" . $jobDivisi->kode . "-" . strtoupper(substr($request->kategori, 0, 3));
            }

            $formDataInvoice = [
                "job_divisi_id" => $request->job_divisi_id,
                "kategori"      => $request->kategori,
                "kode"          => $kode,
                "versi"         => $nextVersi,
                "created_by"    => Auth::user()->id
            ];

            // Selalu create invoice baru (tidak menghapus yang lama)
            $invoice = Invoice::create($formDataInvoice);

            $formDetailInv = collect($request->input("item_print_inv", []))->map(function ($item) use ($invoice) {
                return [
                    "invoice_id"               => $invoice->id,
                    "job_divisi_form_order_id" => $item,
                    "created_at"               => now(),
                    "updated_at"               => now()
                ];
            });

            if (!empty($formDetailInv)) {
                InvoiceDetail::insert($formDetailInv->toArray());
            }

            DB::commit();

            // Melempar parameter invoice_id agar PDF yang dicetak tepat sasaran ke versi baru tersebut
            if ($request->kategori === "bank") {
                return redirect()->route("pdf.invoice-bank.print", [
                    "id" => $jobDivisi->id,
                    "kategori" => $request->kategori,
                    "invoice_id" => $invoice->id
                ]);
            } elseif ($request->kategori === "penjual" || $request->kategori === "pembeli") {
                return redirect()->route("pdf.invoice.print", [
                    "id" => $jobDivisi->id,
                    "kategori" => $request->kategori,
                    "invoice_id" => $invoice->id
                ]);
            } elseif ($request->kategori === "umum") {
                return redirect()->route("pdf.invoice-umum.print", [
                    "id" => $jobDivisi->id,
                    "kategori" => $request->kategori,
                    "invoice_id" => $invoice->id
                ]);
            }

            return redirect()->back()->with("success", "Berhasil print invoice");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", "Gagal print invoice, coba beberapa saat lagi: " . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
