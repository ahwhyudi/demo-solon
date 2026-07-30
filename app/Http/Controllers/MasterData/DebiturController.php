<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Debitur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DebiturController extends Controller
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
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $formInsert = collect([]);

            foreach ($request->debiturs as $key => $value) {

                $formInsert->push([
                    "nama" => $value["nama_lengkap"],
                    "nik" => $value["nik"],
                    "nomor_telepon" => $value["nomor_telepon"],
                    "created_at" => now(),
                    "updated_at" => now(),
                    "created_by" => Auth::user()->id
                ]);
            }

            $inserData = Debitur::insert($formInsert->toArray());

            DB::commit();

            return redirect()->back()->with("success", "Berhasil simpan debitur");
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->with("error", $th->getMessage())
                ->withErrors([
                    "msg_error" => "Terjadi kesalahan server"
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
