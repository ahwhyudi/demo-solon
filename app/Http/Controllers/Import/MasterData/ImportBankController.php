<?php

namespace App\Http\Controllers\Import\MasterData;

use App\Http\Controllers\Controller;
use App\Imports\MasterData\ImportBank;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportBankController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->data;

        DB::beginTransaction();

        try {


            $importData = Excel::import(new ImportBank(), $file);

            DB::commit();

            return redirect()->back()->with("success", "Berhasil import data bank");
        } catch (Exception $th) {
            //throw $th;
            DB::rollBack();

            return redirect()->back()->with("error", "Gagal import data bank");
        }
    }
}
