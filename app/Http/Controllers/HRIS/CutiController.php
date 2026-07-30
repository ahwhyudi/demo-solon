<?php

namespace App\Http\Controllers\HRIS;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\User;
use App\Services\Notifikasi\NotifikasiServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $notifikasiServis;

    public function __construct(NotifikasiServis $notifikasiServis)
    {
        $this->notifikasiServis = $notifikasiServis;
    }
    public function index()
    {
        $items = Cuti::query();

        if (!Auth::user()->can("hris/semua-karyawan")) {
            $items->where("user_id", Auth::user()->id);
        }

        return view("pages.hris.cuti.index", [
            'items' => $items->orderBy("id", "desc")->paginate(12),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.hris.cuti.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataValidasi = [
            "keterangan" => "required",
            "start_date" => "required",
            "end_date" => "required",
        ];

        if ($request->file) {
            $dataValidasi["file"] = "image";
        }

        $validasi = Validator::make($request->all(), $dataValidasi);

        if ($validasi->fails()) {
            return redirect()->back()->with("error", "Data belum lengkap")->withInput();
        }

        // $cuti = Cuti::create([
        //     "keterangan" => $request->keterangan,
        //     "start_date" => $request->start_date,
        //     "end_date" => $request->end_date,
        //     "file" => $request->file ? $request->file->store("cuti", "public") : null,
        //     "user_id" => Auth::user()->id
        // ]);

        $cuti = Cuti::create([
            "keterangan" => $request->keterangan,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "file" => $request->file
                ? $request->file->store("cuti", "public")
                : null,
            "user_id" => Auth::user()->id
        ]);

        $superAdmins = User::whereHas('roles', function ($query) {

            $query->where('name', 'super admin');
        })->get();

        foreach ($superAdmins as $admin) {

            $this->notifikasiServis->create(
                $admin->id,
                "Pengajuan Cuti",
                Auth::user()->name . " mengajukan cuti",
                route('hris.cuti.index')
            );
        }

        return redirect()->route("hris.cuti.index")->with("success", "Berhasil menambahkan cuti baru");
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
        $cuti = Cuti::findOrFail($id);
        $cuti->status = $request->status;
        $cuti->approved_by = Auth::user()->id;
        $cuti->save();

        return redirect()->route("hris.cuti.index")->with("success", "Berhasil $request->status cuti");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
