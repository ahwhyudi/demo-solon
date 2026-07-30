<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\StatusDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = StatusDetail::with("status")->paginate(12);

        return Inertia::render('MasterData/Status/DataStatus', [
            "items" => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('MasterData/Status/CreateDataStatus');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $status = Status::create([
                "nama" => $request->nama,
            ]);

            foreach ($request->dataStatus as $key => $value) {
                $statusDetail = StatusDetail::create([
                    "status_id" => $status->id,
                    "nama" => $value["nama"],
                ]);
            }

            DB::commit();

            Session::flash('success', 'Data Berhasil Ditambahkan');

            return to_route('master-data.status.index');
        } catch (Exception $th) {
            DB::rollBack();

            Session::flash('error', 'Data Gagal Ditambahkan');

            return to_route('master-data.status.index');
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
