<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankKepalaLegal;
use App\Models\BankKepalaMarketing;
use App\Models\BankLegal;
use App\Models\BankMarketing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Bank::select("id", "nama", "is_active")->orderBy('id', 'desc')->get();

        return view("pages.MasterData.Bank.index", compact("items"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bank = Bank::orderBy("nama")->get();

        return view("pages.MasterData.Bank.create", compact("bank"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validasi = Validator::make($request->all(), [
            'parent_id' => ['nullable', 'integer', 'exists:banks,id'],

            // nama bank harus unik dalam 1 parent yang sama
            'nama' => [
                'required',
                'string',
                'max:100'
            ],

            'nama_pimpinan_sekarang'   => ['required', 'string', 'max:100'],
            'nama_pimpinan_selanjutnya' => ['nullable', 'string', 'max:100'],

            'start_kemitraan' => ['nullable', 'date'],
            'end_kemitraan'   => ['nullable', 'date', 'after_or_equal:start_kemitraan'],

            // Array dinamis
            'kepala_legal'            => ['nullable', 'array'],
            'kepala_legal.*.nama'     => ['required_with:kepala_legal', 'string', 'max:100'],

            'legal'                   => ['nullable', 'array'],
            'legal.*.nama'            => ['required_with:legal', 'string', 'max:100'],

            'kepala_marketing'        => ['nullable', 'array'],
            'kepala_marketing.*.nama' => ['required_with:kepala_marketing', 'string', 'max:100'],

            'marketing'               => ['nullable', 'array'],
            'marketing.*.nama'        => ['required_with:marketing', 'string', 'max:100'],
        ], [
            'nama.required' => 'Nama bank wajib diisi.',
            'nama.unique'   => 'Nama bank sudah terdaftar pada parent yang sama.',
            'end_kemitraan.after_or_equal' => 'End Kemitraan harus sama atau setelah Start Kemitraan.',
        ], [
            'parent_id' => 'Parent',
            'nama' => 'Nama Bank',
            'nama_pimpinan_sekarang' => 'Nama Pimpinan Sekarang',
            'nama_pimpinan_selanjutnya' => 'Nama Pimpinan Selanjutnya',
            'start_kemitraan' => 'Start Kemitraan',
            'end_kemitraan' => 'End Kemitraan',

            'kepala_legal.*.nama' => 'Nama Kepala Legal',
            'legal.*.nama' => 'Nama Legal',
            'kepala_marketing.*.nama' => 'Nama Kepala Marketing',
            'marketing.*.nama' => 'Nama Marketing',
        ]);

        if ($validasi->fails()) {
            return redirect()->back()->with("error", "Data belum lengkap")->withInput();
        }

        DB::beginTransaction();

        try {

            $bank = Bank::create([
                "parent_id" => $request->parent_id,
                'nama' => $request->nama,
                "nama_pimpinan_sekarang" => $request->nama_pimpinan_sekarang,
                "nama_pimpinan_selanjutnya" => $request->nama_pimpinan_selanjutnya,
                'start_kemitraan' => $request->start_kemitraan,
                'end_kemitraan' => $request->end_kemitraan,
                "is_active" => 1,
            ]);

            $kepala_legal = collect($request->kepala_legal ?? [])->map(function ($item) use ($bank) {
                return [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            });
            $insertKepalaLegal = BankKepalaLegal::insert($kepala_legal->toArray());

            $legal = collect($request->legal ?? [])->map(function ($item) use ($bank) {
                return [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            });
            $insertLegal = BankLegal::insert($legal->toArray());

            $kepala_marketing = collect($request->kepala_marketing ?? [])->map(function ($item) use ($bank) {
                return [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            });
            $BankKepalaMarketing = BankKepalaMarketing::insert($kepala_marketing->toArray());

            $marketing = collect($request->marketing ?? [])->map(function ($item) use ($bank) {
                return [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            });
            $BankMarketing = BankMarketing::insert($marketing->toArray());

            DB::commit();
            return redirect()->route("master-data.bank.index")->with("success", "Data Berhasil Disimpan");
        } catch (Exception $th) {
            DB::rollBack();
            // dd($th);
            return redirect()->back()->with("error", "Terjadi kesalahan server : " . $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bank = Bank::with("kepalaLegal", "kepalaMarketing")
            ->find($id);

        return view("pages.MasterData.Bank.detail", compact("bank"));
    }

    public function edit(string $id)
    {
        $bank = Bank::with([
            'kepalaLegal',
            'legal',
            'kepalaMarketing',
            'marketing'
        ])->findOrFail($id);

        // list bank untuk dropdown parent (hindari pilih dirinya sendiri jadi parent)
        $banks = Bank::where('id', '!=', $bank->id)->orderBy('nama')->get();

        return view("pages.MasterData.Bank.edit", compact("bank", "banks"));
    }

    public function update(Request $request, string $id)
    {
        $bank = Bank::findOrFail($id);

        $validasi = Validator::make($request->all(), [
            'parent_id' => ['nullable', 'integer', 'exists:banks,id', 'not_in:' . $bank->id],

            'nama' => [
                'required',
                'string',
                'max:100',
                // optional (kalau mau bener2 unik per parent):
                // Rule::unique('banks', 'nama')
                //     ->ignore($bank->id)
                //     ->where(fn($q) => $q->where('parent_id', $request->parent_id)),
            ],

            'nama_pimpinan_sekarang'    => ['required', 'string', 'max:100'],
            'nama_pimpinan_selanjutnya' => ['nullable', 'string', 'max:100'],

            'start_kemitraan' => ['nullable', 'date'],
            'end_kemitraan'   => ['nullable', 'date', 'after_or_equal:start_kemitraan'],

            'kepala_legal'             => ['nullable', 'array'],
            'kepala_legal.*.nama'      => ['required_with:kepala_legal', 'string', 'max:100'],

            'legal'                    => ['nullable', 'array'],
            'legal.*.nama'             => ['required_with:legal', 'string', 'max:100'],

            'kepala_marketing'         => ['nullable', 'array'],
            'kepala_marketing.*.nama'  => ['required_with:kepala_marketing', 'string', 'max:100'],

            'marketing'                => ['nullable', 'array'],
            'marketing.*.nama'         => ['required_with:marketing', 'string', 'max:100'],
        ], [
            'nama.required' => 'Nama bank wajib diisi.',
            'nama.unique'   => 'Nama bank sudah terdaftar pada parent yang sama.',
            'end_kemitraan.after_or_equal' => 'End Kemitraan harus sama atau setelah Start Kemitraan.',
        ], [
            'parent_id' => 'Parent',
            'nama' => 'Nama Bank',
            'nama_pimpinan_sekarang' => 'Nama Pimpinan Sekarang',
            'nama_pimpinan_selanjutnya' => 'Nama Pimpinan Selanjutnya',
            'start_kemitraan' => 'Start Kemitraan',
            'end_kemitraan' => 'End Kemitraan',

            'kepala_legal.*.nama' => 'Nama Kepala Legal',
            'legal.*.nama' => 'Nama Legal',
            'kepala_marketing.*.nama' => 'Nama Kepala Marketing',
            'marketing.*.nama' => 'Nama Marketing',
        ]);

        if ($validasi->fails()) {
            return redirect()->back()->with("error", "Data belum lengkap")->withInput();
        }

        DB::beginTransaction();

        try {
            $bank->update([
                "parent_id" => $request->parent_id,
                'nama' => $request->nama,
                "nama_pimpinan_sekarang" => $request->nama_pimpinan_sekarang,
                "nama_pimpinan_selanjutnya" => $request->nama_pimpinan_selanjutnya,
                'start_kemitraan' => $request->start_kemitraan,
                'end_kemitraan' => $request->end_kemitraan,
            ]);

            // ====== RESET CHILD (delete then insert) ======
            BankKepalaLegal::where('bank_id', $bank->id)->delete();
            BankLegal::where('bank_id', $bank->id)->delete();
            BankKepalaMarketing::where('bank_id', $bank->id)->delete();
            BankMarketing::where('bank_id', $bank->id)->delete();

            // helper filter: buang yang kosong
            $kepalaLegal = collect($request->kepala_legal ?? [])
                ->filter(fn($x) => isset($x['nama']) && trim($x['nama']) !== '')
                ->map(fn($item) => [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ])->values();

            $legal = collect($request->legal ?? [])
                ->filter(fn($x) => isset($x['nama']) && trim($x['nama']) !== '')
                ->map(fn($item) => [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ])->values();

            $kepalaMarketing = collect($request->kepala_marketing ?? [])
                ->filter(fn($x) => isset($x['nama']) && trim($x['nama']) !== '')
                ->map(fn($item) => [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ])->values();

            $marketing = collect($request->marketing ?? [])
                ->filter(fn($x) => isset($x['nama']) && trim($x['nama']) !== '')
                ->map(fn($item) => [
                    "bank_id" => $bank->id,
                    "nama" => $item["nama"],
                    "created_at" => now(),
                    "updated_at" => now(),
                ])->values();

            if ($kepalaLegal->isNotEmpty()) BankKepalaLegal::insert($kepalaLegal->toArray());
            if ($legal->isNotEmpty()) BankLegal::insert($legal->toArray());
            if ($kepalaMarketing->isNotEmpty()) BankKepalaMarketing::insert($kepalaMarketing->toArray());
            if ($marketing->isNotEmpty()) BankMarketing::insert($marketing->toArray());

            DB::commit();
            return redirect()->route("master-data.bank.index")->with("success", "Data Berhasil Diupdate");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", "Terjadi kesalahan server : " . $th->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Bank::find($id);

        if (!$item) {
            Session::flash('error', 'Data Tidak Ditemukan');
            return to_route('master-data.bank.index');
        }

        $item->delete();

        Session::flash('success', 'Data Berhasil Dihapus');

        return to_route('master-data.bank.index');
    }
}
