<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\SettingStepOps;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StepOpsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = SettingStepOps::orderBy('urutan')->orderBy('id')->get();

        return view('pages.setting.step-ops.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('setting.step-ops.index', ['open-modal' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_step' => 'required|string|max:255',
            'data_objek' => 'nullable|boolean',
            'penugasan_staff' => 'nullable|boolean',
            'konfirmasi' => 'nullable|boolean',
            'order' => 'required|array|min:1',
            'order.*' => 'required|string',
        ], [], [
            'nama_step' => 'Nama Step',
            'data_objek' => 'Data Objek',
            'penugasan_staff' => 'Penugasan Staff',
            'konfirmasi' => 'Konfirmasi',
            'order' => 'Urutan Step',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data belum lengkap')->withInput();
        }

        DB::beginTransaction();

        try {
            $newItem = SettingStepOps::create([
                'nama_step' => $request->nama_step,
                'data_objek' => $request->boolean('data_objek'),
                'penugasan_staff' => $request->boolean('penugasan_staff'),
                'konfirmasi' => $request->boolean('konfirmasi'),
                'urutan' => 0,
            ]);

            $this->applyOrder($request->order, $newItem->id, null);

            DB::commit();

            return redirect()->route('setting.step-ops.index')->with('success', 'Setting step ops berhasil ditambahkan');
        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan server : ' . $exception->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = SettingStepOps::findOrFail($id);

        return view('pages.setting.step-ops.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = SettingStepOps::findOrFail($id);
        $items = SettingStepOps::orderBy('urutan')->orderBy('id')->get();

        return view('pages.setting.step-ops.edit', compact('item', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = SettingStepOps::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_step' => 'required|string|max:255',
            'data_objek' => 'nullable|boolean',
            'penugasan_staff' => 'nullable|boolean',
            'konfirmasi' => 'nullable|boolean',
            'order' => 'required|array|min:1',
            'order.*' => 'required|string',
        ], [], [
            'nama_step' => 'Nama Step',
            'data_objek' => 'Data Objek',
            'penugasan_staff' => 'Penugasan Staff',
            'konfirmasi' => 'Konfirmasi',
            'order' => 'Urutan Step',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data belum lengkap')->withInput();
        }

        DB::beginTransaction();

        try {
            $item->update([
                'nama_step' => $request->nama_step,
                'data_objek' => $request->boolean('data_objek'),
                'penugasan_staff' => $request->boolean('penugasan_staff'),
                'konfirmasi' => $request->boolean('konfirmasi'),
            ]);

            $this->applyOrder($request->order, null, $item->id);

            DB::commit();

            return redirect()->route('setting.step-ops.index')->with('success', 'Setting step ops berhasil diupdate');
        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan server : ' . $exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = SettingStepOps::findOrFail($id);

        DB::beginTransaction();

        try {
            $item->delete();
            $this->normalizeOrder();

            DB::commit();

            return redirect()->route('setting.step-ops.index')->with('success', 'Setting step ops berhasil dihapus');
        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan server : ' . $exception->getMessage());
        }
    }

    private function applyOrder(array $tokens, ?int $newId = null, ?int $currentId = null): void
    {
        $position = 1;
        $processed = [];

        foreach ($tokens as $token) {
            if ($token === 'new' && $newId) {
                SettingStepOps::whereKey($newId)->update(['urutan' => $position]);
                $processed[] = $newId;
                $position++;
                continue;
            }

            if ($token === 'current' && $currentId) {
                SettingStepOps::whereKey($currentId)->update(['urutan' => $position]);
                $processed[] = $currentId;
                $position++;
                continue;
            }

            if (str_starts_with($token, 'existing-')) {
                $id = (int) str_replace('existing-', '', $token);

                if ($id > 0) {
                    SettingStepOps::whereKey($id)->update(['urutan' => $position]);
                    $processed[] = $id;
                    $position++;
                }
            }
        }

        $remaining = SettingStepOps::whereNotIn('id', $processed)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        foreach ($remaining as $row) {
            $row->update(['urutan' => $position]);
            $position++;
        }
    }

    private function normalizeOrder(): void
    {
        $position = 1;
        $items = SettingStepOps::orderBy('urutan')->orderBy('id')->get();

        foreach ($items as $row) {
            $row->update(['urutan' => $position]);
            $position++;
        }
    }
}
