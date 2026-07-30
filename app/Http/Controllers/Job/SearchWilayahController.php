<?php

namespace App\Http\Controllers\Job;

use App\Models\Desa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class SearchWilayahController extends Controller
{

    // public function cariProvinsi(Request $request){
    //     $query = $request->get('q');
    //     $provinsi = Provinsi::where('name', 'like', "%$query%")
    //     ->orderBy('name')
    //     ->limit(10)
    //     ->get()
    //     ->map(function ($provinsi){
    //         return [
    //             'value'=>(string)$provinsi->kode,
    //             'name'=>$provinsi->name
    //         ];
    //     });
    //     return response()->json($provinsi);
    // }

    // public function cariKota(Request $request){
    //     $query = $request->get('q');
    //     $kota = Kota::where('name', 'like', "%$query%")
    //     ->orderBy('name')
    //     ->limit(10)
    //     ->get()
    //     ->map(function ($kota){
    //         return [
    //             'value'=>(string)$kota->id_kota,
    //             'name'=>$kota->name
    //         ];
    //     });
    //     return response()->json($kota);
    // }

    // public function cariKecamatan(Request $request){
    //     $query = $request->get('q');
    //     $kecamatan = Kecamatan::where('name', 'like', "%$query%")
    //     ->orderBy('name')
    //     ->limit(10)
    //     ->get()
    //     ->map(function($kecamatan){
    //         return [
    //             'id'=>(string)$kecamatan->id_kecamatan,
    //             'name'=>$kecamatan->name,
    //         ];
    //     });
    //     return response()->json($kecamatan);
    // }

    public function cariDesa(Request $request)
    {
        $search = $request->search;

        if ($search === "") {
            $data = Desa::limit(7)
                ->with("kecamatan")
                ->get();
        } else {
            $data = Desa::where('name', 'like', '%' . $search . '%')
                ->with("kecamatan")
                ->limit(7)
                ->get();
        }

        $response = [];
        foreach ($data as $item) {
            $response[] = [
                "id" => $item->id,
                "text" => "$item->name, {$item->kecamatan->name}"
            ];
        }

        return response()->json($response);
    }
}
