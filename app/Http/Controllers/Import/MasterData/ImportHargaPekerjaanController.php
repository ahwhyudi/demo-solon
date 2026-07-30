<?php

namespace App\Http\Controllers\Import\MasterData;

use App\Http\Controllers\Controller;
use App\Imports\MasterData\ImportHargaPekerjaan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportHargaPekerjaanController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->data;

        DB::beginTransaction();

        try {

            $importData = Excel::import(new ImportHargaPekerjaan(), $file);

            DB::commit();
            return redirect()->back()->with("success", "Berhasil simpan pekerjaan");
        } catch (Exception $th) {

            DB::rollBack();

            return redirect()->back()->with("error", $th->getMessage());
        }
    }
}
