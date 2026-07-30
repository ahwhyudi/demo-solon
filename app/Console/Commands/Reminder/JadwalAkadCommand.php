<?php

namespace App\Console\Commands\Reminder;

use App\Models\JobDivisi;
use App\Models\User;
use App\Services\Wa\SendTextWaServis;
use Carbon\Carbon;
use Illuminate\Console\Command;

class JadwalAkadCommand extends Command
{
    protected $signature = 'reminder:jadwal-akad';
    protected $description = 'Reminder H-1 Akad';

    public function __construct(protected SendTextWaServis $sendTextWaServis)
    {

        parent::__construct(); // WAJIB!
    }

    public function handle()
    {
        // H-1 => besok
        $targetDate = Carbon::tomorrow()->startOfDay(); // besok 00:00
        $targetDateEnd = Carbon::tomorrow()->endOfDay(); // besok 23:59:59

        $items = JobDivisi::query()
            ->whereBetween('tanggal_rencana_akad', [$targetDate, $targetDateEnd])
            ->where('status', 'Pra Akad')
            ->with("jenisAkad", "debitur")
            ->whereHas("debitur")
            ->orderBy('tanggal_rencana_akad', "asc")
            ->get();

        if ($items->isEmpty()) {
            $this->info("Tidak ada jadwal akad untuk H-1.");
            return;
        }

        // Format tanggal hari besok
        $reminderDate = Carbon::tomorrow()->locale('id')->isoFormat('dddd, D MMMM Y');
        // misal: Senin, 27 April 2026

        $userSuperAdmin = User::query()->whereHas("roles", function ($query) {
            $query->where("name", "super admin");
        })
            ->where("phone", "!=", null)
            ->select("name", "phone")
            ->get();

        foreach ($userSuperAdmin as $user) {
            $reminderText = "Selamat malam {$user->name}, ijin reminder untuk jadwal akad hari, $reminderDate :\n\n";

            foreach ($items as $index => $job) {
                $nama_debitur = implode(', ', $job->debitur->pluck("nama")->toArray());

                $jam = Carbon::parse($job->tanggal_rencana_akad)->format('H:i');
                $nama = $nama_debitur ?? '-'; // ganti dengan field nama yang sesuai
                $reminderText .= ($index + 1) . ". $nama - {$job->jenisAkad->nama} (pukul $jam)\n";
            }

            // Cek output di console
            $this->info($reminderText);

            $this->sendTextWaServis->execute($user->phone, $reminderText);

            // jeda random antar 1 detik sampai 5 menit
            $delaySeconds = rand(1, 300);
            $this->info("Jeda {$delaySeconds} detik sebelum kirim ke user berikutnya...");
            sleep($delaySeconds);
        }
    }
}
