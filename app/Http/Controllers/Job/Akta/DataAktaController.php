<?php

namespace App\Http\Controllers\Job\Akta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Job\UpdateStatusJobDivisiController;
use App\Models\JobDivisiFormOrder;
use App\Models\StatusJobOps;
use App\Models\User;
use App\Services\Akta\AktaService;
use App\Services\Notifikasi\NotifikasiServis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataAktaController extends Controller
{

    public function __construct(
        protected AktaService $aktaService,
        protected NotifikasiServis $notifikasiServis
    ) {}

    /**
     * Tampilkan daftar data akta dengan kategori notaris, ppat, legalisasi.
     */
    public function index(Request $request, ?string $tipe = null)
    {
        $tipe = $tipe ?: 'notaris';

        $client = config('app.notaris', 'default');

        $workflow = config("workflow.akta.$client.$tipe")
            ?? config("workflow.akta.default.$tipe")
            ?? [];
        $data = $this->aktaService->getIndexData($request, $tipe);

        foreach ($data["items"] as $item) {
            $status = $item->statusJobOps->last()->status ?? 'Belum dikerjakan';

            // $item->nextStep = $this->getNextStep($workflow, $status)["name"] ?? "Belum diproses";
            $nextStep = $this->getNextStep($workflow, $status);

            $item->nextStep = $nextStep['name'] ?? $status;
        }
        $prosesOptions = JobDivisiFormOrder::query()
            ->where('kategori', $tipe)
            ->distinct()
            ->pluck('nama');

        $statusOptions = StatusJobOps::query()
            ->distinct()
            ->pluck('status');

        $userOptions = User::orderBy('name')->get();

        $bankOptions = \App\Models\Bank::orderBy('nama')->get();

        return view("pages.Job.Akta.index", $data, [
            'prosesOptions' => $prosesOptions,
            'statusOptions' => $statusOptions,
            'userOptions' => $userOptions,
            'bankOptions' => $bankOptions
        ]);
    }

    private function getNextStep(array $workflow, string $currentStatus): ?array
    {

        if ($currentStatus === 'Belum diproses') {
            return $workflow[0] ?? null;
        }

        $currentIndex = collect($workflow)->search(function ($step) use ($currentStatus) {
            return ($step['name'] ?? null) === $currentStatus;
        });

        if ($currentIndex === false) {
            return $workflow[0] ?? null;
        }

        return $workflow[$currentIndex + 1] ?? null;
    }

    /**
     * Simpan penugasan atau penyelesaian data akta.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {

            $status = $request->tipe === 'tolak'
                ? $request->reject_status
                : $request->next_status;

            // ambil staff terakhir yang ngerjain
            $lastStaff = StatusJobOps::where('job_divisi_form_order_id', $request->form_id)
                ->whereNotNull('user_id')
                ->orderBy('id', 'desc')
                ->first();

            StatusJobOps::create([
                'created_by' => Auth::id(),
                'job_divisi_form_order_id' => $request->form_id,
                'status' => $status,
                'user_id' => $request->staff ? $request->staff : ($lastStaff->user_id ?? null),
                'next_user' => $request->next_qc ? $request->next_qc : ($lastStaff->next_user ?? null),
                'keterangan' => $request->keterangan,
                "status_penolakan" => $request->tipe === 'tolak'
                    ? $request->current_status
                    : null
            ]);

            $updateStatus = new UpdateStatusJobDivisiController();

            $formOrder = JobDivisiFormOrder::with("jobDivisi")
                ->find($request->form_id);

            $updateSelesai = $updateStatus->updateSelesai(
                $formOrder->jobDivisi->id
            );

            $kategori = strtolower(trim($formOrder->kategori));

            if ($kategori == 'ppat') {

                $link = route('job.akta.data.filter', 'ppat');
                $title = "Penugasan Akta PPAT";
            } elseif ($kategori == 'notaris') {

                $link = route('job.akta.data.index');
                $title = "Penugasan Akta Notaris";
            } else {

                $link = '#';
                $title = "Penugasan";
            }

            // notif penugasan
            if ($request->staff) {

                $this->notifikasiServis->create(
                    $request->staff,
                    $title,
                    "Anda memiliki penugasan akta",
                    $link,
                    $formOrder->jobDivisi->id,
                    $formOrder->id
                );
            }


            // notif QC hanya ketika submit hasil kerja staff
            if (
                !$request->staff &&
                $request->tipe === 'next_step'
            ) {

                $lastQc = StatusJobOps::where(
                    'job_divisi_form_order_id',
                    $request->form_id
                )
                    ->whereNotNull('next_user')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastQc?->next_user) {

                    $this->notifikasiServis->create(
                        $lastQc->next_user,
                        "QC Akta",
                        "Terdapat data akta yang perlu di QC",
                        $link,
                        $formOrder->jobDivisi->id,
                        $formOrder->id
                    );
                }
            }

            // notif penolakan ke staff sebelumnya
            if ($request->tipe === 'tolak' && $lastStaff?->user_id) {

                $this->notifikasiServis->create(
                    $lastStaff->user_id,
                    "Akta Ditolak",
                    "Data akta ditolak dengan status : " . $request->reject_status,
                    $link,
                    $formOrder->jobDivisi->id,
                    $formOrder->id
                );
            }

            DB::commit();

            return redirect()->back()->with(
                'success',
                'Berhasil simpan data akta'
            );
        } catch (Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('error', "Gagal simpan data, Coba beberapa saat lagi");
        }
    }

    /**
     * Detail satuan job akta (opsional).
     */
    public function show(string $id)
    {
        $job_divisi = JobDivisiFormOrder::with('user', 'jobDivisi')->find($id);

        if (!$job_divisi) {
            return redirect()->back()->with('msg_error', 'Data tidak ditemukan');
        }

        $userOps = User::orderBy('name', "asc")->get()->map(fn($user) => [
            'value' => (string)$user->id,
            'label' => $user->name,
        ]);
    }
}
