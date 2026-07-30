<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\HargaPekerjaan;
use App\Models\JobDivisi;
use App\Models\JobDivisiFormOrder;
use App\Models\JobDivisiObjek;
use App\Models\Pekerjaan;
use App\Services\PerubahanHarga\TambahPerubahanHarga;
use App\Services\PerubahanHarga\TolakPerubahanHarga;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetailJobFormOrderController extends Controller
{


    public function __construct(
        protected TolakPerubahanHarga $tolakPerubahanHargaServis,
        protected TambahPerubahanHarga $tambahPerubahanHarga
    ) {}

    public function approvePerubahanHarga(Request $request, $job_id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $job = JobDivisi::with("perubahanHarga.detail")->find($job_id);

            $perubahanHarga = $job->perubahanHarga;

            $perubahanHarga->status = $request->status;
            $perubahanHarga->user_id = Auth::user()->id;
            $perubahanHarga->save();


            if ($request->status === "tolak") {
                $tolakPerubahanHarga = $this->tolakPerubahanHargaServis->execute($job, $perubahanHarga);
            }

            DB::commit();
            return redirect()->back()
                ->with("success", "Status Perubahan Harga Berhasil Diubah");
        } catch (Exception $th) {
            DB::rollBack();
            // dd($th);
            return redirect()->back()->with("error", "Gagal melakukan perubahan, coba beberapa saat lagi");
        }
    }

    public function addItemJobDivisi(Request $request)
    {
        $request->validate([
            "proses" => "required",
        ]);

        DB::beginTransaction();

        try {
            $proses = Pekerjaan::find($request->proses);

            $formData = [
                "job_divisi_id" => $request->job_divisi_id,
                "pekerjaan_id" => $proses->id,
                "nama" => $proses->nama,
                "kategori" => $proses->kategori,
                "masuk_invoice" => $request->masuk_invoice ?? 0,
                "harga_jual" => 0,
                "harga_modal" => 0,
                "harga_proses" => 0,
                "lama_proses" => 1,
                "status" => "approved",
                "created_by" => Auth::user()->id
            ];

            $objek = JobDivisiObjek::query()
                ->where("id", $request->objek_id)
                ->with("desa.kecamatan.kota")
                ->first();

            if ($objek) {
                $hargaPekerjaan = HargaPekerjaan::where("pekerjaan_id", $proses->id)
                    ->where("kota_id", $objek->desa->kecamatan->kota->id)
                    ->first();

                $formData = [
                    ...$formData,
                    "harga_jual" => $hargaPekerjaan->harga_jual,
                    "harga_modal" => $hargaPekerjaan->harga_limit,
                    "harga_proses" => 0,
                    "lama_proses" => $hargaPekerjaan->lama_proses
                ];
            }

            $item = JobDivisiFormOrder::create($formData);


            DB::commit();
            return redirect()->back()->with("success", "Item Berhasil Ditambahkan");
        } catch (Exception $th) {
            return redirect()->back()->with("error", "Item Gagal Ditambahkan");
        }
    }

    public function updateStatus(Request $request)
    {
        $item = JobDivisiFormOrder::find($request->id);

        $item->status = $request->status;
        $item->save();

        return redirect()->back()->with("success", "Status Berhasil Diubah");
    }

    public function updateHarga(Request $request)
    {


        DB::beginTransaction();

        try {

            $jobDivisi = JobDivisi::query()
                ->with("formOrder")
                ->where("id", $request->job_id)
                ->first();

            // simpan data lama
            $tambahPerubahanHarga = $this->tambahPerubahanHarga->execute($request->job_id, $jobDivisi->formOrder);

            $items = $request->form_order;

            $formOrder = $jobDivisi->formOrder;

            $array_form_order = collect([]);
            // dd($items);
            foreach ($items as $form_order_id => $item) {
                $updateFormOrder =  $formOrder->where("id", $form_order_id)->first();

                $updateFormOrder->update([
                    "harga_jual" => str_replace(".", "", $item["harga_jual"] ?? 0),
                    "harga_modal" => str_replace(".", "", $item["harga_modal"] ?? 0),
                    "diskon" => str_replace(".", "", $item["diskon"] ?? 0),
                    "masuk_invoice" => $item["masuk_invoice"] ?? 0
                ]);

                $array_form_order->push($form_order_id);
            }

            JobDivisiFormOrder::query()
                ->whereNotIn("id", $array_form_order->toArray())
                ->where("job_divisi_id", $request->job_id)
                ->delete();

            DB::commit();

            return redirect()->back()->with("success", "Berhasil update harga jual");
        } catch (Exception $th) {
            DB::rollBack();
            // dd($th);
            return redirect()->back()->with("error", "Terjadi kesalahan server");
        }
    }
}
