<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\DeveloperLegal;
use App\Models\DeveloperMarketing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Developer::with(['marketing', 'legal'])->orderBy("id", 'desc')->get();

        return view('pages.MasterData.Developer.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.MasterData.Developer.create");
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

            $developer = new Developer();
            $developer->nama_perumahan = $request->nama_perumahan;
            $developer->nama_pt = $request->nama_perusahaan;
            $developer->email_perusahaan = $request->email_perusahaan;
            $developer->nama_pimpinan = $request->nama_pimpinan;
            $developer->save();

            $formDataMarketing = collect($request->marketing)->map(function ($row) use ($developer) {
                return [
                    ...$row,
                    "created_at" => now(),
                    "updated_at" => now(),
                    "developer_id" => $developer->id
                ];
            });

            $insertMarketing = DeveloperMarketing::insert($formDataMarketing->toArray());

            $formDataLegal = collect($request->legal)->map(function ($row) use ($developer) {
                return [
                    ...$row,
                    "created_at" => now(),
                    "updated_at" => now(),
                    "developer_id" => $developer->id
                ];
            });


            $insertLegal = DeveloperLegal::insert($formDataLegal->toArray());

            DB::commit();

            Session::flash('success', 'Data berhasil disimpan');
            return redirect()->back();
        } catch (Exception $th) {
            DB::rollBack();

            dd($th);
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
        $item = Developer::with(['marketing', 'legal'])->findOrFail($id);
        return view('pages.MasterData.Developer.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validasi = Validator::make($request->all(), [
            "nama_perumahan" => "required",
            "nama_pt" => "required",
            "nama_pimpinan" => "required",
            "email_perusahaan" => "required"
        ]);

        if ($validasi->fails()) {
            Session::flash('error', 'Data belum lengkap');
            return redirect()->back()->withErrors($validasi)->withInput();
        }

        DB::beginTransaction();

        try {
            $developer = Developer::findOrFail($id);
            $developer->update([
                "nama_perumahan" => $request->nama_perumahan,
                "nama_pt" => $request->nama_pt,
                "nama_pimpinan" => $request->nama_pimpinan,
                "email_perusahaan" => $request->email_perusahaan,
            ]);
            $developer->marketing()->delete();


            if ($request->filled('marketing')) {
                $formDataMarketing = collect($request->marketing)->map(function ($row) use ($developer) {
                    return [
                        ...$row,
                        "developer_id" => $developer->id,
                        "created_at" => now(),
                        "updated_at" => now()
                    ];
                });
                DeveloperMarketing::insert($formDataMarketing->toArray());
            }
            $developer->legal()->delete();
            if ($request->filled('legal')) {
                $formDataLegal = collect($request->legal)->map(function ($row) use ($developer) {
                    return [
                        ...$row,
                        "developer_id" => $developer->id,
                        "created_at" => now(),
                        "updated_at" => now()
                    ];
                });
                DeveloperLegal::insert($formDataLegal->toArray());
            }
            DB::commit();
            Session::flash('success', "$request->nama berhasil diupdate");
            return redirect()->route('master-data.developer.index');;
        } catch (Exception $e) {
            // dd($e);
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
        $item = Developer::find($id);
        $nama = $item->nama;
        $item->delete();

        Session::flash('success', "$nama berhasil dihapus");
        return redirect()->back();
    }
}
