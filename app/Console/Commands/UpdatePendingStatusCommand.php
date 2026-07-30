<?php

namespace App\Console\Commands;

use App\Models\JobDivisi;
use App\Models\JobPending;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\alert;

class UpdatePendingStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-pending-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::channel("cron_job_log")->info("Update pending status started");
        $this->alert("Update pending status");
        $jobDivisi = JobDivisi::where("status", "Akad")
            ->whereDate("tanggal_estimasi_selesai", "<", Carbon::now())
            ->get();

        foreach ($jobDivisi as $key => $value) {
            DB::beginTransaction();
            try {
                $value->status = "Pending";
                $value->is_pending = 1;
                $value->deleted_at = now();
                $value->save();

                $insertPending = JobPending::create([
                    "job_divisi_id" => $value->id,
                    "start_date" => Carbon::now(),
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->error($th->getMessage());
            }
        }

        $this->info("Update pending status selesai");
    }
}
