<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Divisi::select("id", "nama")->orderBy('id', 'desc')->get();

        return view("pages.MasterData.Divisi.index", compact("items"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.MasterData.Divisi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $data = Divisi::create([
            'nama' => $request->nama,
        ]);

        Session::flash('success', 'Data Berhasil Ditambahkan');

        return to_route('master-data.divisi.index');
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
    public function edit(string $id) {
        $item = Divisi::findOrFail($id);
        return view('pages.MasterData.Divisi.edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'nama'=>'required|string|max:255'
        ]);
        $divisi = Divisi::findOrFail($id);
        $divisi->update([
            'nama'=>$validate['nama'],
        ]);
        return redirect()->route('master-data.divisi.index')->with('success', 'Divisi Berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Divisi::find($id);

        if (!$data) {
            Session::flash('error', 'Data Tidak Ditemukan');
            return to_route('master-data.divisi.index');
        }

        $data->delete();

        Session::flash('success', 'Data Berhasil Dihapus');

        return to_route('master-data.divisi.index');
    }
}
