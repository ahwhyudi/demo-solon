<?php

namespace App\Http\Controllers\Import\MasterData;

use App\Http\Controllers\Controller;
use App\Imports\MasterData\ImportBroker;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportBrokerController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->data;

        DB::beginTransaction();

        try {

            $importData = Excel::import(new ImportBroker(), $file);

            DB::commit();

            return redirect()->back()->with("success", "Berhasil import data broker");
        } catch (Exception $th) {
            DB::rollBack();
            dd($th);
            return redirect()->back()->with("error", "Gagal import data broker");
        }
    }
}
