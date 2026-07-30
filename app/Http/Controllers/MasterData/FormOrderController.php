<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterDataFormOrder;
use App\Models\MasterDataFormOrderDetail;
use App\Models\Pekerjaan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FormOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = MasterDataFormOrder::withCount('details')
            ->latest()
            ->get();

        return view("pages.MasterData.PaketPekerjaan.index", compact("items"));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proses = Pekerjaan::orderBy("nama", "asc")->get();


        return view("pages.MasterData.PaketPekerjaan.create", compact("proses"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $formdata = [
                "nama" => $request->nama,
                "sla_internal" => $request->sla_internal,
                "sla_eksternal" => $request->sla_eksternal,
                "jenis_data" => implode(',', $request->jenis_data)
            ];

            if ($request->data_pendukung) {
                $formdata["data_pendukung"] = implode(",", $request->data_pendukung);
            }

            $masterData = MasterDataFormOrder::create($formdata);

            $dataInsertDetail = collect([]);
            foreach ($request->pekerjaan as $key => $value) {

                $dataInsertDetail->push([
                    "master_data_form_order_id" => $masterData->id,
                    "pekerjaan_id" => $value,
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
            }

            $insertData = MasterDataFormOrderDetail::insert($dataInsertDetail->toArray());


            DB::commit();

            return redirect()->route("master-data.form-order.index")->with("success", "Berhasil tambah master data form order");
        } catch (Exception $th) {
            DB::rollBack();

            return redirect()->back()->with("error", $th->getMessage())->withInput();
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
        $formOrder = MasterDataFormOrder::with('details')->findOrFail($id);
        $proses = Pekerjaan::orderBy('nama', 'asc')->get();
        $proses = Pekerjaan::orderBy("nama", "asc")->get();
        $jenis_data = explode(",", $formOrder->jenis_data);
        $data_pendukung = explode(",", $formOrder->data_pendukung);


        return view("pages.MasterData.PaketPekerjaan.edit", compact("formOrder", "data_pendukung", "proses", "jenis_data"));
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_data' => 'nullable|array',
            'pekerjaan' => 'required|array|min:1',
            'pekerjaan.*' => 'required|exists:pekerjaans,id',
        ]);

        DB::beginTransaction();
        try {
            $formOrder = MasterDataFormOrder::findOrFail($id);

            $formdata = [
                "nama" => $request->nama,
                "sla_internal" => $request->sla_internal,
                "sla_eksternal" => $request->sla_eksternal,
                "jenis_data" => implode(',', $request->jenis_data)
            ];

            if ($request->data_pendukung) {
                $formdata["data_pendukung"] = implode(",", $request->data_pendukung);
            }

            $formOrder->update($formdata);

            MasterDataFormOrderDetail::where("master_data_form_order_id", $formOrder->id)->delete();

            $dataInsertDetail = collect([]);
            foreach ($request->pekerjaan as $key => $value) {

                $dataInsertDetail->push([
                    "master_data_form_order_id" => $formOrder->id,
                    "pekerjaan_id" => $value,
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
            }

            $insertData = MasterDataFormOrderDetail::insert($dataInsertDetail->toArray());

            DB::commit();
            return redirect()->route('master-data.form-order.index')->with('success', 'Berhasil update form order');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', "Terjadi kesalahan server : {$e->getMessage()}");
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $formOrder = MasterDataFormOrder::findOrFail($id);

            // hapus detail dulu
            MasterDataFormOrderDetail::where('master_data_form_order_id', $formOrder->id)->delete();

            // hapus master
            $formOrder->delete();

            DB::commit();
            return redirect()->route('master-data.form-order.index')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}