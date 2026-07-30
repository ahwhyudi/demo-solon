<?php

namespace App\Http\Controllers\Arsip;

use App\Http\Controllers\Controller;
use App\Models\BundleNomor;
use App\Models\NomorPpat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tipe = $request->tipe ?? "notaris";
        $bulan = $request->filled("bulan") ? (int) $request->bulan : null;
        $tahun = (int) ($request->tahun ?? now()->year);

        if ($bulan !== null && ($bulan < 1 || $bulan > 12)) {
            $bulan = null;
        }

        if ($tipe === "ppat") {
            $bulan = null;
        }

        $items = BundleNomor::orderBy("id", "desc")
            ->where("kategori", $tipe)
            ->where("tahun", $tahun)
            ->when($bulan, function ($query) use ($bulan) {
                $query->where("bulan", $bulan);
            })
            ->with(["nomorPpat.formOrder.jobDivisi.debitur", "nomorPpat.formOrder.jobDivisi.objek", "nomorPpat.pekerjaan"])
            ->withCount("nomorPpat")
            ->paginate(12)
            ->withQueryString();

        $dataNomor = collect();
        $tahunList = collect(range(now()->year, now()->subYears(10)->year));

        $bulanList = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember",
        ];

        if ($request->ajax() || $request->wantsJson()) {
            $dataNomor = NomorPpat::query()
                ->with(["formOrder.jobDivisi.debitur", "formOrder.jobDivisi.objek", "pekerjaan"])
                ->where("kategori", $tipe)
                ->whereYear("tanggal", $tahun)
                ->when($bulan, function ($query) use ($bulan) {
                    $query->whereMonth("tanggal", $bulan);
                })
                ->where("rekanan", 0)
                ->whereNull("bundle_id")
                ->orderByRaw("CAST(nomor AS UNSIGNED) ASC")
                ->orderBy("nomor", "asc")
                ->get()
                ->map(function ($item) {
                    $namaDebitur = $item->form_order_id
                        ? ($item->nama_debitur_notaris_pengambil ?? "-")
                        : ($item->formOrder?->jobDivisi?->debitur?->pluck("nama")->implode(", ") ?: "-");

                    $objek = $item->form_order_id
                        ? ($item->objek_notaris_pengambil ?? "-")
                        : ($item->formOrder?->jobDivisi?->objek?->pluck("no_sertifikat")->implode(", ") ?: "-");

                    return [
                        "nomor" => $item->nomor,
                        "nama_proses" => $item->form_order_id
                            ? ($item->pekerjaan?->nama ?? "-")
                            : ($item->formOrder?->nama ?? "-"),
                        "nama_debitur" => $namaDebitur,
                        "objek" => $objek,
                    ];
                });

            return response()->json([
                "dataNomor" => $dataNomor,
                "bulan" => $bulan,
                "tahun" => $tahun,
                "labelPeriode" => $bulan ? (($bulanList[$bulan] ?? $bulan) . " " . $tahun) : "Tahun " . $tahun,
            ]);
        }

        return view("pages.arsip.index", compact("items", "dataNomor", "tipe", "bulan", "tahun", "bulanList", "tahunList"));
    }

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
    public function store(Request $request)
    {
        $request->validate([
            "nomor" => "required|string",
            "kategori" => "required|string",
            "tahun" => "required|integer",
            "bulan" => "nullable|integer|min:1|max:12",
            "nomor_akta" => "required|array|min:1|max:50",
        ], [
            "nomor_akta.required" => "Pilih minimal satu nomor akta.",
            "nomor_akta.max" => "Maksimal 50 nomor dalam satu bundle.",
        ]);

        $selectedNomor = collect($request->nomor_akta)
            ->map(fn($nomor) => (int) $nomor)
            ->sort()
            ->values();

        $isNomorBerurutan = $selectedNomor
            ->every(fn($nomor, $index) => $index === 0 || $nomor === $selectedNomor[$index - 1] + 1);

        if (!$isNomorBerurutan) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Nomor akta harus berurutan tanpa loncat. Contoh: 1, 2, 3.");
        }

        $bulan = $request->kategori === "ppat" ? null : $request->bulan;

        DB::beginTransaction();

        try {
            $bundle = BundleNomor::create([
                "user_id" => Auth::id(),
                "nomor" => $request->nomor,
                "kategori" => $request->kategori,
                "tahun" => $request->tahun,
                "bulan" => $bulan,
                "keterangan" => $request->keterangan,
            ]);

            NomorPpat::query()
                ->where("kategori", $request->kategori)
                ->whereYear("tanggal", $request->tahun)
                ->when($bulan, function ($query) use ($bulan) {
                    $query->whereMonth("tanggal", $bulan);
                })
                ->whereIn("nomor", $request->nomor_akta)
                ->whereNull("bundle_id")
                ->update(["bundle_id" => $bundle->id]);

            DB::commit();

            return redirect()
                ->route("arsip.bundle.index", [
                    "tipe" => $request->kategori,
                    "bulan" => $bulan,
                    "tahun" => $request->tahun,
                ])
                ->with("success", "Berhasil simpan bundle nomor");
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with("error", $th->getMessage());
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
        DB::beginTransaction();

        try {
            $bundle = BundleNomor::findOrFail($id);

            NomorPpat::query()
                ->where("bundle_id", $bundle->id)
                ->update(["bundle_id" => null]);

            $bundle->delete();

            DB::commit();

            return redirect()->back()->with("success", "Berhasil hapus bundle nomor");
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with("error", $th->getMessage());
        }
    }
}
