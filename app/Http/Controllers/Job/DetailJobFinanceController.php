<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\JobDivisi;
use App\Models\JobDivisiFinance;
use App\Models\JobDivisiFormOrder;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DetailJobFinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $job_divisi = JobDivisi::with("finance.user", "formOrder", "debitur")->find(decodeHashIds($request->job_divisi));
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
        // dd($request->all());
        $job_divisi = JobDivisi::with("finance", 'pnbps',)->find($request->job_divisi_id);

        DB::beginTransaction();

        try {
            $dataInsertFinance = collect([]);

            foreach ($request->metode_pembayaran as $key => $metode_pembayaran) {
                $keterangan = $request->keterangan[$key];
                $total = str_replace(".", "", $request->total[$key]);
                $tipe = $request->tipe[$key];
                $peruntukan = $request->peruntukan[$key];
                $date = $request->date[$key];


                $dataInsertFinance->push([
                    // Tambahkan ?? null supaya aman kalau user nggak milih invoice
                    "invoice_id" => $request->invoice[$key] ?? null,

                    "job_divisi_id" => $job_divisi->id,
                    "tanggal" => Carbon::parse($date)->format("Y-m-d"),
                    "total" => $total,
                    "keterangan" => $keterangan,
                    "metode_pembayaran" => $metode_pembayaran,
                    "created_at" => now(),
                    "updated_at" => now(),
                    "user_id" => Auth::user()->id,
                    "created_by" => Auth::user()->id,
                    "tipe" => $tipe,
                    "peruntukan" => $peruntukan,
                    "status" => "Disetujui"
                ]);
            }


            $insertFinance = JobDivisiFinance::insert($dataInsertFinance->toArray());

            DB::commit();
            return redirect()->back()->with("success", "Data berhasil disimpan");
        } catch (Exception $th) {
            DB::rollBack();
            // dd($th->getMessage());
            return redirect()->back()->with("error", "Finance gagal disimpan, coba beberapa saat lagi");
        }
    }

    public function getPeruntukan(string $tipe, ?array $peruntukanRequestArray, int $key): ?string
    {
        // Check if the current entry is 'in'
        if (strtolower($tipe) === 'in') {
            // Check if the peruntukan array exists and has a value for this key
            if ($peruntukanRequestArray && isset($peruntukanRequestArray[$key])) {
                return $peruntukanRequestArray[$key];
            }
            // Fallback for 'in' if the field somehow wasn't submitted (maybe required validation is needed here)
            return '';
        }

        // If the tipe is 'out', we don't need a peruntukan value, so return null.
        return null;
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
