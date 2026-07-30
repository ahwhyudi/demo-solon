<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\BrokerLegal;
use App\Models\BrokerMarketing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BrokerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Broker::with('marketing')->orderBy("id", 'desc')->get();

        return view('pages.MasterData.Broker.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.MasterData.Broker.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validasi = Validator::make($request->all(), [
            "nama_perumahan" => "required",
            "nama_perusahaan" => "required",
            "email_perusahaan" => "required",
        ]);

        if ($validasi->fails()) {

            Session::flash('error', 'Data belum lengkap');
            return redirect()->back()->withErrors($validasi->errors()->first())->withInput();
        }

        DB::beginTransaction();

        try {

            $broker = new Broker();
            $broker->nama_perumahan = $request->nama_perumahan;
            $broker->nama_pt = $request->nama_perusahaan;
            $broker->email_perusahaan = $request->email_perusahaan;
            $broker->nama_pimpinan = $request->nama_pimpinan;
            $broker->save();

            $formDataMarketing = collect($request->marketing)->map(function ($row) use ($broker) {
                return [
                    ...$row,
                    "created_at" => now(),
                    "updated_at" => now(),
                    "broker_id" => $broker->id
                ];
            });

            $insertMarketing = BrokerMarketing::insert($formDataMarketing->toArray());

            DB::commit();

            Session::flash('success', 'Data berhasil disimpan');
            return redirect()->back();
        } catch (Exception $th) {
            DB::rollBack();

            // dd($th);
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
        $item = Broker::with('marketing')->findOrFail($id);
        return view('pages.MasterData.Broker.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validasi = Validator::make($request->all(), [
            "nama_perumahan" => "required",
            "nama_pt" => "required",
            "email_perusahaan" => "required|email",
        ]);

        if ($validasi->fails()) {
            // dd($validasi);
            Session::flash('error', 'Data belum lengkap');
            return redirect()
                ->back()
                ->withErrors($validasi)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $broker = Broker::findOrFail($id);

            $broker->update([
                "nama_perumahan"   => $request->nama_perumahan,
                "nama_pt"           => $request->nama_pt,
                "email_perusahaan"  => $request->email_perusahaan,
                "nama_pimpinan"     => $request->nama_pimpinan,
            ]);

            // 🔹 Hapus marketing lama
            $broker->marketing()->delete();

            // 🔹 Insert marketing baru (kalau ada)
            if ($request->filled('marketing')) {
                $formDataMarketing = collect($request->marketing)->map(function ($row) use ($broker) {
                    return [
                        ...$row,
                        "broker_id"  => $broker->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ];
                });

                BrokerMarketing::insert($formDataMarketing->toArray());
            }

            DB::commit();

            Session::flash('success', 'Data broker dan marketing berhasil diperbarui');
            return redirect()->route('master-data.broker.index');
        } catch (Exception $e) {

            DB::rollBack();
            Session::flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Broker::find($id);
        $nama = $item->nama;
        $item->delete();

        Session::flash('success', "$nama berhasil dihapus");
        return redirect()->back();
    }
}
