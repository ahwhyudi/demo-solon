<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\JobDivisiFormOrder;
use App\Models\MasterDataFormOrder;
use App\Models\MasterDataFormOrderDetail;
use App\Models\NomorPpat;
use App\Models\Pekerjaan;
use App\Services\Akta\InputNomorServis;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\PenomoranExport;
use Maatwebsite\Excel\Facades\Excel;


class PenomoranController extends Controller
{

    public function __construct(protected InputNomorServis $inputNomorServis) {}

    public function index(Request $request, $kategori = null)
    {
        $masterPekerjaan = Pekerjaan::query()->get();

        $items = NomorPpat::with("formOrder.jobDivisi", "pekerjaan")
            ->when($kategori, function ($query) use ($kategori) {
                $query->where("kategori", $kategori);
            })
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('pages.Laporan.nomor-notaris.index', compact('items', 'kategori', 'masterPekerjaan'));
    }

    public function inputNomorRekanan(Request $request)
    {

        $request->validate([
            "group_proses" => "required",
            "tanggal_nomor" => "required",
            "notaris_pengambil" => "required",
            "objek_notaris_pengambil" => "required",
            "nama_debitur_notaris_pengambil" => "required",
        ]);

        try {
            $kategori = $request->kategori;
            $tanggal_nomor = $request->tanggal_nomor;

            $nomor = $this->inputNomorServis->execute($kategori, $tanggal_nomor);
            // dd($nomor);
            $formData = [
                'user_id' => Auth::user()->id,
                "nomor" => $nomor,
                "rekanan" => 0,
                "notaris_pengambil" => $request->notaris_pengambil,
                "objek_notaris_pengambil" => $request->objek_notaris_pengambil,
                "nama_debitur_notaris_pengambil" => $request->nama_debitur_notaris_pengambil,
                "tanggal" => Carbon::parse($request->tanggal_nomor),
                "kategori" => $request->kategori,
                "form_order_id" => $request->group_proses,
                "job_divisi_form_order_id" => 0
            ];

            NomorPpat::create($formData);
            return redirect()->back()->with("success", "Berhasil simpan nomor");
        } catch (Exception $th) {

            return redirect()->back()->with("error", "Gagal simpan nomor");
        }
    }

    public function export($kategori = null)
    {
        return Excel::download(
            new PenomoranExport($kategori),
            'laporan-penomoran-' . $kategori . '.xlsx'
        );
    }

    public function edit($id)
    {
        $item = NomorPpat::findOrFail($id);

        $masterPekerjaan = Pekerjaan::all();

        return view(
            'pages.Laporan.nomor-notaris.edit',
            compact('item', 'masterPekerjaan')
        );
    }
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'group_proses' => 'required',
    //         'tanggal_nomor' => 'required',
    //         'notaris_pengambil' => 'required',
    //         'nama_debitur_notaris_pengambil' => 'required',
    //     ]);

    //     try {

    //         $nomor = NomorPpat::findOrFail($request->id);

    //         $nomor->update([
    //             'form_order_id' => $request->group_proses,
    //             'notaris_pengambil' => $request->notaris_pengambil,
    //             'objek_notaris_pengambil' => $request->objek_notaris_pengambil,
    //             'nama_debitur_notaris_pengambil' => $request->nama_debitur_notaris_pengambil,
    //             'tanggal' => Carbon::parse($request->tanggal_nomor),
    //         ]);

    //         return redirect()
    //             ->route('laporan.nomor-notaris.index', $nomor->kategori)
    //             ->with('success', 'Data berhasil diupdate');
    //     } catch (\Exception $e) {

    //         return back()->with('error', $e->getMessage());
    //     }
    // }
    public function update(Request $request)
    {
        $item = NomorPpat::findOrFail($request->id);

        $item->update([
            'form_order_id' => $request->group_proses,
            'notaris_pengambil' => $request->notaris_pengambil,
            'nama_debitur_notaris_pengambil' => $request->nama_debitur_notaris_pengambil,
            'objek_notaris_pengambil' => $request->objek_notaris_pengambil,
            'tanggal' => $request->tanggal_nomor,
        ]);

        return back()->with('success', 'Berhasil update data');
    }
}
