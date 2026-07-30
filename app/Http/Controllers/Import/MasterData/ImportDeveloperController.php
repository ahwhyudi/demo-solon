<?php

namespace App\Http\Controllers\Import\MasterData;

use App\Http\Controllers\Controller;
use App\Imports\MasterData\ImportDeveloper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportDeveloperController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->data;

        DB::beginTransaction();


        try {
            // dd($file);
            $importData = Excel::import(new ImportDeveloper(), $file);

            DB::commit();

            return redirect()->back()->with("success", "Berhasil import data developer");
        } catch (Exception $th) {
            DB::rollBack();

            return redirect()->back()->with("error", "Gagal import data developer");
        }
    }
}
