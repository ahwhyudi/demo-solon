<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Notaris;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NotarisController extends Controller
{
    public function index(Request $request)
    {
        $item = Notaris::query()->first();

        return view('pages.MasterData.Notaris.index', compact('item'));
    }

    public function create()
    {
        return redirect()->route('master-data.notaris.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nomor' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'nama_kantor' => 'nullable|string|max:255',
            'alamat_kantor' => 'nullable|string',
            'npwp' => 'nullable|string|max:100',
            'nomor_sk' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'foto_ktp' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:2048',
            'tanda_tangan' => 'nullable|image|max:2048',
            'stempel' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            Session::flash('error', 'Data belum lengkap');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $item = Notaris::query()->first();

            $data = [
                'nama' => $request->nama,
                'nomor' => $request->nomor,
                'email' => $request->email,
                'nama_kantor' => $request->nama_kantor,
                'alamat_kantor' => $request->alamat_kantor,
                'npwp' => $request->npwp,
                'nomor_sk' => $request->nomor_sk,
            ];

            if ($request->hasFile('foto')) {
                if ($item?->foto) {
                    Storage::disk('public')->delete($item->foto);
                }

                $data['foto'] = $request->file('foto')->store('notaris/foto', 'public');
            }

            if ($request->hasFile('foto_ktp')) {
                if ($item?->foto_ktp) {
                    Storage::disk('public')->delete($item->foto_ktp);
                }

                $data['foto_ktp'] = $request->file('foto_ktp')->store('notaris/foto-ktp', 'public');
            }

            if ($request->hasFile('logo')) {
                if ($item?->logo) {
                    Storage::disk('public')->delete($item->logo);
                }

                $data['logo'] = $request->file('logo')->store('notaris/logo', 'public');
            }

            if ($request->hasFile('tanda_tangan')) {
                if ($item?->tanda_tangan) {
                    Storage::disk('public')->delete($item->tanda_tangan);
                }

                $data['tanda_tangan'] = $request->file('tanda_tangan')->store('notaris/tanda-tangan', 'public');
            }

            if ($request->hasFile('stempel')) {
                if ($item?->stempel) {
                    Storage::disk('public')->delete($item->stempel);
                }

                $data['stempel'] = $request->file('stempel')->store('notaris/stempel', 'public');
            }

            if ($item) {
                $item->update($data);
            } else {
                Notaris::create($data);
            }

            DB::commit();

            return redirect()->route('master-data.notaris.index')->with('success', 'Profil notaris berhasil disimpan');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(string $id)
    {
        return redirect()->route('master-data.notaris.index');
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nomor' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'nama_kantor' => 'nullable|string|max:255',
            'alamat_kantor' => 'nullable|string',
            'npwp' => 'nullable|string|max:100',
            'nomor_sk' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'foto_ktp' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:2048',
            'tanda_tangan' => 'nullable|image|max:2048',
            'stempel' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            Session::flash('error', 'Data belum lengkap');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $item = Notaris::findOrFail($id);

            $data = [
                'nama' => $request->nama,
                'nomor' => $request->nomor,
                'email' => $request->email,
                'nama_kantor' => $request->nama_kantor,
                'alamat_kantor' => $request->alamat_kantor,
                'npwp' => $request->npwp,
                'nomor_sk' => $request->nomor_sk,
            ];

            if ($request->hasFile('foto')) {
                if ($item->foto) {
                    Storage::disk('public')->delete($item->foto);
                }

                $data['foto'] = $request->file('foto')->store('notaris/foto', 'public');
            }

            if ($request->hasFile('foto_ktp')) {
                if ($item->foto_ktp) {
                    Storage::disk('public')->delete($item->foto_ktp);
                }

                $data['foto_ktp'] = $request->file('foto_ktp')->store('notaris/foto-ktp', 'public');
            }

            if ($request->hasFile('logo')) {
                if ($item->logo) {
                    Storage::disk('public')->delete($item->logo);
                }

                $data['logo'] = $request->file('logo')->store('notaris/logo', 'public');
            }

            if ($request->hasFile('tanda_tangan')) {
                if ($item->tanda_tangan) {
                    Storage::disk('public')->delete($item->tanda_tangan);
                }

                $data['tanda_tangan'] = $request->file('tanda_tangan')->store('notaris/tanda-tangan', 'public');
            }

            if ($request->hasFile('stempel')) {
                if ($item->stempel) {
                    Storage::disk('public')->delete($item->stempel);
                }

                $data['stempel'] = $request->file('stempel')->store('notaris/stempel', 'public');
            }

            $item->update($data);

            DB::commit();

            return redirect()->route('master-data.notaris.index')->with('success', 'Data notaris berhasil diupdate');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        return redirect()->route('master-data.notaris.index')->with('error', 'Profil notaris tunggal tidak dapat dihapus dari menu ini');
    }
}
