<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\Setting;
use App\Models\skNotaris;
use App\Models\skPpat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache; // <-- Tambah facade cache

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menyimpan data ke cache selamanya sampai dihapus manual saat ada perubahan data
        $item = Cache::rememberForever('setting_perusahaan', function () {
            return Setting::with([
                'skNotaris.kota',
                'skPpat.kota',
            ])->first();
        });

        return view('pages.setting.perusahaan.index', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kotas = Kota::orderBy('name')->get();

        return view('pages.setting.perusahaan.create', compact('kotas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes()
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data belum lengkap')
                ->withInput();
        }

        DB::beginTransaction();

        $newLogo = null;
        $newFileNotaris = null;
        $newFilePpat = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | Simpan perusahaan
            |--------------------------------------------------------------------------
            */

            $payload = $this->payload($request);

            $newLogo = $payload['logo'];

            $setting = Setting::create($payload);

            /*
            |--------------------------------------------------------------------------
            | Simpan SK Notaris
            |--------------------------------------------------------------------------
            */

            if (
                $request->sk_notaris ||
                $request->alamat_notaris ||
                $request->kota_notaris_id ||
                $request->tanggal_sk_notaris ||
                $request->hasFile('file_sk_notaris')
            ) {

                $newFileNotaris = $request->hasFile('file_sk_notaris')
                    ? $request->file('file_sk_notaris')->store('sk-notaris', 'public')
                    : null;

                skNotaris::create([
                    'perusahaan_id' => $setting->id,
                    'sk_kemenkumham' => $request->sk_notaris,
                    'alamat' => $request->alamat_notaris,
                    'kota_id' => $request->kota_notaris_id,
                    'tanggal_sk' => $request->tanggal_sk_notaris,
                    'file' => $newFileNotaris,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan SK PPAT
            |--------------------------------------------------------------------------
            */

            if (
                $request->sk_ppat ||
                $request->alamat_ppat ||
                $request->kota_ppat_id ||
                $request->tanggal_sk_ppat ||
                $request->hasFile('file_sk_ppat')
            ) {

                $newFilePpat = $request->hasFile('file_sk_ppat')
                    ? $request->file('file_sk_ppat')->store('sk-ppat', 'public')
                    : null;

                skPpat::create([
                    'perusahaan_id' => $setting->id,
                    'sk_kemenkumham' => $request->sk_ppat,
                    'alamat' => $request->alamat_ppat,
                    'kota_id' => $request->kota_ppat_id,
                    'tanggal_sk' => $request->tanggal_sk_ppat,
                    'file' => $newFilePpat,
                ]);
            }

            DB::commit();

            // Hapus cache agar data baru langsung termuat pada request selanjutnya
            Cache::forget('setting_perusahaan');

            return redirect()
                ->route('setting.perusahaan.index')
                ->with('success', 'Data perusahaan berhasil ditambahkan');
        } catch (Exception $exception) {

            DB::rollBack();

            if ($newLogo) {
                Storage::disk('public')->delete($newLogo);
            }

            if ($newFileNotaris) {
                Storage::disk('public')->delete($newFileNotaris);
            }

            if ($newFilePpat) {
                Storage::disk('public')->delete($newFilePpat);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan server : ' . $exception->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Menggunakan cache juga pada method show jika sewaktu-waktu dibutuhkan
        $item = Cache::rememberForever('setting_perusahaan_' . $id, function () use ($id) {
            return Setting::with([
                'skNotaris.kota',
                'skPpat.kota',
            ])->findOrFail($id);
        });

        return view('pages.setting.perusahaan.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Setting::with([
            'skNotaris.kota',
            'skPpat.kota',
        ])->findOrFail($id);
        $kotas = Kota::orderBy('name')->get();

        return view('pages.setting.perusahaan.edit', compact('item', 'kotas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Setting::with([
            'skNotaris.kota',
            'skPpat.kota',
        ])->findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            [],
            $this->attributes()
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data belum lengkap')
                ->withInput();
        }

        DB::beginTransaction();

        $oldLogo = $item->logo;

        $newLogo = null;
        $newFileNotaris = null;
        $newFilePpat = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | Update perusahaan
            |--------------------------------------------------------------------------
            */

            $payload = $this->payload($request, $item);

            $newLogo = $payload['logo'];

            $item->update($payload);

            if ($request->hasFile('logo') && $oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            /*
            |--------------------------------------------------------------------------
            | Update SK Notaris
            |--------------------------------------------------------------------------
            */

            $oldFileNotaris = $item->skNotaris?->file;

            $newFileNotaris = $request->hasFile('file_sk_notaris')
                ? $request->file('file_sk_notaris')->store('sk-notaris', 'public')
                : $oldFileNotaris;

            skNotaris::updateOrCreate(
                [
                    'perusahaan_id' => $item->id,
                ],
                [
                    'sk_kemenkumham' => $request->sk_notaris,
                    'alamat' => $request->alamat_notaris,
                    'kota_id' => $request->kota_notaris_id,
                    'tanggal_sk' => $request->tanggal_sk_notaris,
                    'file' => $newFileNotaris,
                ]
            );

            if ($request->hasFile('file_sk_notaris') && $oldFileNotaris) {
                Storage::disk('public')->delete($oldFileNotaris);
            }

            /*
            |--------------------------------------------------------------------------
            | Update SK PPAT
            |--------------------------------------------------------------------------
            */

            $oldFilePpat = $item->skPpat?->file;

            $newFilePpat = $request->hasFile('file_sk_ppat')
                ? $request->file('file_sk_ppat')->store('sk-ppat', 'public')
                : $oldFilePpat;

            skPpat::updateOrCreate(
                [
                    'perusahaan_id' => $item->id,
                ],
                [
                    'sk_kemenkumham' => $request->sk_ppat,
                    'alamat' => $request->alamat_ppat,
                    'kota_id' => $request->kota_ppat_id,
                    'tanggal_sk' => $request->tanggal_sk_ppat,
                    'file' => $newFilePpat,
                ]
            );

            if ($request->hasFile('file_sk_ppat') && $oldFilePpat) {
                Storage::disk('public')->delete($oldFilePpat);
            }

            DB::commit();

            // Bersihkan cache lama saat update data berhasil dilakukan
            Cache::forget('setting_perusahaan');
            Cache::forget('setting_perusahaan_' . $id);

            return redirect()
                ->route('setting.perusahaan.index')
                ->with('success', 'Data perusahaan berhasil diupdate');
        } catch (Exception $exception) {

            DB::rollBack();

            if ($newLogo && $newLogo !== $oldLogo) {
                Storage::disk('public')->delete($newLogo);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan server : ' . $exception->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Setting::with([
            'skNotaris.kota',
            'skPpat.kota',
        ])->findOrFail($id);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Hapus logo
            |--------------------------------------------------------------------------
            */

            if ($item->logo) {
                Storage::disk('public')->delete($item->logo);
            }

            /*
            |--------------------------------------------------------------------------
            | Hapus file SK Notaris
            |--------------------------------------------------------------------------
            */

            if ($item->skNotaris?->file) {
                Storage::disk('public')->delete($item->skNotaris->file);
            }

            /*
            |--------------------------------------------------------------------------
            | Hapus file SK PPAT
            |--------------------------------------------------------------------------
            */

            if ($item->skPpat?->file) {
                Storage::disk('public')->delete($item->skPpat->file);
            }

            $item->delete();

            DB::commit();

            // Bersihkan cache setelah data dihapus permanen
            Cache::forget('setting_perusahaan');
            Cache::forget('setting_perusahaan_' . $id);

            return redirect()
                ->route('setting.perusahaan.index')
                ->with('success', 'Data perusahaan berhasil dihapus');
        } catch (Exception $exception) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan server : ' . $exception->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    */

    private function rules(): array
    {
        return [
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'sk_notaris' => 'nullable|string|max:255',
            'alamat_notaris' => 'nullable|string',
            'kota_notaris_id' => 'nullable|exists:kotas,id',
            'tanggal_sk_notaris' => 'nullable|date',
            'file_sk_notaris' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',

            'sk_ppat' => 'nullable|string|max:255',
            'alamat_ppat' => 'nullable|string',
            'kota_ppat_id' => 'nullable|exists:kotas,id',
            'tanggal_sk_ppat' => 'nullable|date',
            'file_sk_ppat' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    private function attributes(): array
    {
        return [
            'nama_perusahaan' => 'Nama Perusahaan',
            'alamat' => 'Alamat',
            'telepon' => 'Telepon',
            'email' => 'Email',
            'logo' => 'Logo',
            'foto_profil' => 'Foto Profil',
            'foto_ktp' => 'Foto KTP',

            'sk_notaris' => 'SK Notaris',
            'alamat_notaris' => 'Alamat Notaris',
            'kota_notaris_id' => 'Kota Notaris',
            'tanggal_sk_notaris' => 'Tanggal SK Notaris',
            'file_sk_notaris' => 'File SK Notaris',

            'sk_ppat' => 'SK PPAT',
            'alamat_ppat' => 'Alamat PPAT',
            'kota_ppat_id' => 'Kota PPAT',
            'tanggal_sk_ppat' => 'Tanggal SK PPAT',
            'file_sk_ppat' => 'File SK PPAT',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Payload
    |--------------------------------------------------------------------------
    */

    private function payload(Request $request, ?Setting $item = null): array
    {
        return [
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'logo' => $request->hasFile('logo')
                ? $request->file('logo')->store('setting-perusahaan', 'public')
                : ($item?->logo),
            'foto_profil' => $request->hasFile('foto_profil')
                ? $request->file('foto_profil')->store('setting-perusahaan', 'public')
                : ($item?->foto_profil),
            'foto_ktp' => $request->hasFile('foto_ktp')
                ? $request->file('foto_ktp')->store('setting-perusahaan', 'public')
                : ($item?->foto_ktp),
        ];
    }
}
