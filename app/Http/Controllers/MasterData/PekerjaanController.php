<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\HargaPekerjaan;
use App\Models\Kota;
use App\Models\Pekerjaan;
use App\Models\Provinsi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $hargaItems = HargaPekerjaan::orderBy("id", 'desc')
            ->with("pekerjaan", "provinsi", "kota")
            ->get();

        $pekerjaan = Pekerjaan::orderBy("id", 'desc')->get();

        return view("pages.MasterData.Pekerjaan.index", compact("hargaItems", "pekerjaan"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $provinsi = Provinsi::orderBy("name", 'asc')
            ->with('kota')
            ->get();

        $kategori = ['operasional' => 'Operasional', 'pajak' => 'Pajak', 'notaris' => 'Notaris', 'ppat' => 'PPAT', 'legalisasi' => 'Legalisasi', "pnbp_voucher" => "PNBP/ Voucher", "waarmerking" => "Waarmerking", "surat-keluar" => "Surat Keluar", "wasiat" => "Wasiat"];

        return view("pages.MasterData.Pekerjaan.create", compact("provinsi", "kategori"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                "nama" => "required|unique:pekerjaans,nama",
                "kategori" => "required",
            ]
        );

        if ($request->kategori === "operasional") {
            $request->validate(
                [
                    "wilayah" => "array",
                    "harga_limit" => "array",
                    "harga_proses" => "array",
                    "harga_jual" => "array",
                    "lama_pengerjaan" => "array",
                    "wilayah.*" => "required",
                    "harga_limit.*" => "required",
                    "harga_proses.*" => "required",
                    "harga_jual.*" => "required",
                    "lama_pengerjaan.*" => "required",
                ]
            );
        }


        DB::beginTransaction();

        try {
            $data = Pekerjaan::create([
                "nama" => $request->nama,
                "kategori" => $request->kategori,
                "harga_modal" => 0,
                "harga_jual" => 0
            ]);



            if ($request->kategori === "operasional") {
                $kota = Kota::whereIn("id", $request->wilayah)
                    ->with('provinsi')
                    ->get();


                $insertHarga = collect();
                foreach ($request->wilayah as $index => $item) {
                    $getKota = $kota->where("id", $item)->first();
                    $hargaLimit = str_replace(".", "", $request->harga_limit[$index]);
                    $hargaProses = str_replace(".", "", $request->harga_proses[$index] ?? 0);
                    $hargaJual = str_replace(".", "", $request->harga_jual[$index] ?? 0);
                    $lama_pengerjaan = str_replace(".", "", $request->lama_pengerjaan[$index]);

                    $insertHarga->push([
                        "pekerjaan_id" => $data->id,
                        "kota_id" => $item,
                        "provinsi_id" => $getKota->provinsi->id,
                        "harga_limit" => $hargaLimit,
                        "harga_jual" => $hargaJual,
                        "harga_proses" => $hargaProses,
                        "lama_proses" => $lama_pengerjaan,
                        "created_at" => now(),
                        "updated_at" => now()
                    ]);
                }
                $insertDataHarga = HargaPekerjaan::insert($insertHarga->toArray());
            }


            DB::commit();
            return redirect()->route("master-data.pekerjaan.index")->with("success", "Berhasil Tambah Pekerjaan");
        } catch (Exception $th) {
            DB::rollBack();
            dd($th);

            return redirect()->back()->with("error", "Terjadi kesalahan server : {$th->getMessage()}")->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $pekerjaan = Pekerjaan::findOrFail($id);

        $provinsi = Provinsi::orderBy('name', 'asc')
            ->with('kota')
            ->get();

        $hargaItems = HargaPekerjaan::where('pekerjaan_id', $id)
            ->with('provinsi', 'kota')
            ->orderBy('id', 'asc')
            ->get();

        // 🔥 TAMBAH INI (HARUS SAMA KAYAK CREATE)
        $kategori = [
            'operasional' => 'Operasional',
            'pajak' => 'Pajak',
            'notaris' => 'Notaris',
            'ppat' => 'PPAT',
            'legalisasi' => 'Legalisasi',
            "pnbp_voucher" => "PNBP/ Voucher",
            "waarmerking" => "Waarmerking",
            "surat-keluar" => "Surat Keluar",
            "wasiat" => "Wasiat"
        ];

        return view(
            'pages.MasterData.Pekerjaan.edit',
            compact('pekerjaan', 'provinsi', 'hargaItems', 'kategori')
        );
    }
    // public function update(Request $request, string $id)
    // {
    //     // --- VALIDASI ---
    //     // Perlu ignore $id pada unique nama
    //     $validator = Validator::make($request->all(), [
    //         'nama'             => ['required', Rule::unique('pekerjaans', 'nama')->ignore($id)],
    //         'kategori'         => ['required', Rule::in(['operasional', 'pajak', 'notaris', 'ppat', 'legalisasi', 'pnbp_voucher', 'waarmerking', 'surat-keluar', 'wasiat'])],

    //         'wilayah'          => 'required|array|min:1',
    //         'wilayah.*'        => 'required|exists:kotas,id',

    //         'harga_limit'      => 'required|array|min:1',
    //         'harga_limit.*'    => 'required',

    //         'lama_pengerjaan'  => 'required|array|min:1',
    //         'lama_pengerjaan.*' => 'required',
    //     ], [], [
    //         // pesan alias (opsional)
    //         'wilayah.*'        => 'wilayah',
    //         'harga_limit.*'    => 'harga limit',
    //         'lama_pengerjaan.*' => 'lama pengerjaan',
    //     ]);

    //     // Pastikan panjang array konsisten
    //     $validator->after(function ($v) use ($request) {
    //         $len = count($request->input('wilayah', []));
    //         foreach (['harga_limit','lama_pengerjaan'] as $field) {
    //             if (count($request->input($field, [])) !== $len) {
    //                 $v->errors()->add($field, 'Jumlah baris ' . $field . ' tidak konsisten dengan wilayah.');
    //             }
    //         }
    //     });

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }

    //     // Helper untuk hapus titik pemisah ribuan
    //     $unmask = function ($v) {
    //         return is_null($v) ? null : (int) str_replace('.', '', (string) $v);
    //     };

    //     DB::beginTransaction();
    //     try {
    //         // UPDATE header pekerjaan
    //         $pekerjaan = Pekerjaan::findOrFail($id);
    //         $pekerjaan->update([
    //             'nama'        => $request->nama,
    //             'kategori'    => $request->kategori,
    //             // jika memang ada kolom harga_modal/harga_jual di tabel pekerjaan (agregat),
    //             // biarkan 0 atau hitung dari detail sesuai kebutuhan bisnis.
    //             // 'harga_modal' => 0,
    //             // 'harga_jual'  => 0,
    //         ]);

    //         // Ambil daftar baris harga existing untuk pekerjaan ini
    //         $existing = HargaPekerjaan::where('pekerjaan_id', $id)->get();
    //         $existingKotaIds = $existing->pluck('kota_id')->all();

    //         $incomingKotaIds = $request->wilayah; // array of kota_id

    //         // Hitung baris yang harus DIHAPUS (tidak ada lagi di request)
    //         $toDelete = array_diff($existingKotaIds, $incomingKotaIds);
    //         if (!empty($toDelete)) {
    //             HargaPekerjaan::where('pekerjaan_id', $id)
    //                 ->whereIn('kota_id', $toDelete)
    //                 ->delete();
    //         }

    //         // Siapkan data kota+provinsi untuk semua kota yang dikirim
    //         $kotaRows = Kota::whereIn('id', $incomingKotaIds)
    //             ->with('provinsi')
    //             ->get()
    //             ->keyBy('id');

    //         // UPSERT (updateOrCreate) setiap baris
    //         foreach ($incomingKotaIds as $i => $kotaId) {
    //             $kota = $kotaRows[$kotaId] ?? null;
    //             if (!$kota) {
    //                 // fallback defensif, tapi harusnya lolos validasi exists:
    //                 throw new \RuntimeException("Kota ID {$kotaId} tidak ditemukan.");
    //             }

    //             $hargaLimit = $unmask($request->harga_limit[$i] ?? null);
    //             // lama pengerjaan: kalau memang angka (hari) biarkan int; kalau string, simpan string sesuai skema kolom
    //             $lama = $request->lama_pengerjaan[$i] ?? null;

    //             HargaPekerjaan::updateOrCreate(
    //                 [
    //                     'pekerjaan_id' => $pekerjaan->id,
    //                     'kota_id'      => $kotaId,
    //                 ],
    //                 [
    //                     'provinsi_id'  => $kota->provinsi->id,
    //                     'harga_limit'  => $hargaLimit,
    //                     'lama_proses'  => $lama,
    //                 ]
    //             );
    //         }

    //         DB::commit();
    //         return redirect()
    //             ->route('master-data.pekerjaan.index')
    //             ->with('success', 'Berhasil update Pekerjaan');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         dd($e); // saat debug
    //         return back()
    //             ->with('error', 'Terjadi kesalahan server: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    public function update(Request $request, string $id)
    {
        // ========================
        // VALIDASI
        // ========================
        $rules = [
            'nama' => ['required', Rule::unique('pekerjaans', 'nama')->ignore($id)],
            'kategori' => ['required', Rule::in([
                'operasional',
                'pajak',
                'notaris',
                'ppat',
                'legalisasi',
                'pnbp_voucher',
                'waarmerking',
                'surat-keluar',
                'wasiat'
            ])],
        ];

        // ✅ hanya wajib kalau operasional
        if ($request->kategori === 'operasional') {
            $rules += [
                'wilayah'           => 'required|array|min:1',
                'wilayah.*'         => 'required|exists:kotas,id',

                'harga_limit'       => 'required|array|min:1',
                'harga_limit.*'     => 'required',

                'lama_pengerjaan'   => 'required|array|min:1',
                'lama_pengerjaan.*' => 'required',
            ];
        }

        $validator = Validator::make($request->all(), $rules, [], [
            'wilayah.*' => 'wilayah',
            'harga_limit.*' => 'harga limit',
            'lama_pengerjaan.*' => 'lama pengerjaan',
        ]);

        // ========================
        // VALIDASI JUMLAH ARRAY
        // ========================
        if ($request->kategori === 'operasional') {
            $validator->after(function ($v) use ($request) {
                $len = count($request->wilayah ?? []);

                foreach (['harga_limit', 'lama_pengerjaan'] as $field) {
                    if (count($request->$field ?? []) !== $len) {
                        $v->errors()->add($field, "Jumlah baris $field tidak konsisten dengan wilayah.");
                    }
                }
            });
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ========================
        // HELPER FORMAT ANGKA
        // ========================
        $unmask = function ($v) {
            return is_null($v) ? null : (int) str_replace('.', '', (string) $v);
        };

        DB::beginTransaction();
        try {

            // ========================
            // UPDATE PEKERJAAN
            // ========================
            $pekerjaan = Pekerjaan::findOrFail($id);

            $pekerjaan->update([
                'nama'     => $request->nama,
                'kategori' => $request->kategori,
            ]);

            // ========================
            // KALAU BUKAN OPERASIONAL → HAPUS DETAIL
            // ========================
            if ($request->kategori !== 'operasional') {
                HargaPekerjaan::where('pekerjaan_id', $id)->delete();

                DB::commit();
                return redirect()
                    ->route('master-data.pekerjaan.index')
                    ->with('success', 'Berhasil update Pekerjaan');
            }

            // ========================
            // OPERASIONAL → PROSES DETAIL
            // ========================
            $incomingKotaIds = $request->wilayah;

            // ambil data lama
            $existing = HargaPekerjaan::where('pekerjaan_id', $id)->get();
            $existingKotaIds = $existing->pluck('kota_id')->all();

            // hapus yang tidak ada di request
            $toDelete = array_diff($existingKotaIds, $incomingKotaIds);

            if (!empty($toDelete)) {
                HargaPekerjaan::where('pekerjaan_id', $id)
                    ->whereIn('kota_id', $toDelete)
                    ->delete();
            }

            // ambil data kota
            $kotaRows = Kota::whereIn('id', $incomingKotaIds)
                ->with('provinsi')
                ->get()
                ->keyBy('id');

            // ========================
            // UPSERT DATA
            // ========================
            foreach ($incomingKotaIds as $i => $kotaId) {

                $kota = $kotaRows[$kotaId];

                $hargaLimit = $unmask($request->harga_limit[$i] ?? null);
                $lama = $request->lama_pengerjaan[$i] ?? null;

                HargaPekerjaan::updateOrCreate(
                    [
                        'pekerjaan_id' => $pekerjaan->id,
                        'kota_id'      => $kotaId,
                    ],
                    [
                        'provinsi_id'  => $kota->provinsi->id,
                        'harga_limit'  => $hargaLimit,
                        'harga_proses' => 0, // default aman
                        'harga_jual'   => 0, // default aman
                        'lama_proses'  => $lama,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('master-data.pekerjaan.index')
                ->with('success', 'Berhasil update Pekerjaan');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::findOrFail($id);

            // Hapus semua harga terkait pekerjaan ini
            HargaPekerjaan::where('pekerjaan_id', $id)->delete();

            // Hapus master pekerjaan
            $pekerjaan->delete();

            DB::commit();
            return redirect()
                ->route('master-data.pekerjaan.index')
                ->with('success', 'Pekerjaan berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Gagal hapus pekerjaan: ' . $e->getMessage());
        }
    }
}